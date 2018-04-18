<?php
/*
 * Data Seeker for datatable
 * Auth : Kerem ÇAKIR
 */

namespace App\Http\Libraries\DataSeeker;

use Illuminate\Support\Facades\DB;

class DataSeeker
{

    public $fields;

    public $table;

    private $filters;

    public $pk = 'Id';

    public $columns = [];

    public $wheres = [];

    public $afterWheres = [];

    public $groupBy = '';

    public $orders = [];

    public $multipleEvent = true;

    public $transactions = NULL;

    public $joins = [];

    private $skip = 0;

    private $take = 10;

    private $postPrefix = 'post_';

    public $datas;

    private $totalCount;

    private $response = [];

    public $returnType = 'datatable';

    private $activeTexts = ['Aktif', 'Pasif'];

    private $fb;

    public function getDatas($filters = NULL)
    {

        $this->filters = $filters;

        if (isset($this->filters['customActionType']) && $this->filters['customActionType'] == 'group_action') {
            $this->setAction();
        }


        if (count($this->wheres) > 0) {
            $this->totalCount = DB::table($this->table)->where($this->wheres);
        } else {
            $this->totalCount = DB::table($this->table);
        }

        if (!empty($this->groupBy)) {
            $this->totalCount->groupBy($this->groupBy);
        }
        $this->totalCount = $this->totalCount->count();

        if (!$this->datas) {
            $this->setColumns();
            $this->setWheres();
            $this->setOrders();

            $this->datas = DB::table($this->table);

            if (count($this->columns) > 0) {
                $this->datas = $this->datas->select($this->columns);
            }

            if (count($this->joins) > 0) {
                foreach ($this->joins as $table => $join) {
                    $this->datas = $this->datas->join($join['table2'], $join['left'], '=', $join['right']);
                }
            }

            if (count($this->wheres) > 0) {
                $this->datas = $this->datas->where($this->wheres);
            }

            if (!empty($this->groupBy)) {
                $this->datas = $this->datas->groupBy($this->groupBy);
            }

            if (count($this->orders) > 0) {
                foreach ($this->orders as $order) {
                    $this->datas = $this->datas->orderBy($order[0], $order[1]);
                }
            }

            if ($this->skip > 0) {
                $this->datas = $this->datas->offset($this->skip);
            }

            if ($this->take != -1) {
                $this->datas = $this->datas->limit($this->take);
            }

            $this->datas = $this->datas->get();

        }

        switch ($this->returnType) {
            case 'data':
                return $this->datas;
                break;
            default:
                return $this->convertDatas();
        }

    }

    private function setWheres()
    {
        $this->wheres = array_merge($this->wheres, $this->afterWheres);
        foreach ($this->filters as $key => $filter) {
            if (starts_with($key, $this->postPrefix) && $filter != "") {
                $columnName = str_replace($this->postPrefix, '', $key);
                $fieldName = $columnName;
                if (isset($this->fields[$columnName])) {
                    $type = $this->fields[$columnName]['type'];
                    if (isset($this->fields[$columnName]['join'])) {
                        if (!isset($this->fields[$columnName]['join']['find'])) {
                            $columnName = $this->fields[$columnName]['join']['show'];
                        } else {
                            $columnName = $this->fields[$columnName]['join']['find'];
                        }
                    } else {
                        $columnName = $this->table . '.' . $columnName;
                    }

                    switch ($type) {
                        case 'string':
                            $this->wheres[] = [$columnName, 'like', '%' . $filter . '%'];
                            break;
                        case 'timestamp':
                            $startDate = date('Y-m-d H:i:s', strtotime($filter));
                            if (isset($this->fields[$fieldName]['endDate']) && $this->fields[$fieldName]['endDate'] == true) {
                                $this->wheres[] = [$columnName, '>=', $startDate];
                                $endColumnName = $fieldName . '_endDate';
                                if (isset($this->filters[$endColumnName]) && !empty($this->filters[$endColumnName])) {
                                    $endDate = date('Y-m-d H:i:s', strtotime($this->filters[$endColumnName]));
                                    $this->wheres[] = [$columnName, '<=', $endDate];
                                }
                            } else {
                                $this->wheres[] = [$columnName, $startDate];
                            }
                            break;
                        default:
                            $this->wheres[] = [$columnName, $filter];
                    }
                }
            }
        }
    }

    private function setOrders()
    {
        if (isset($this->filters['order']) && count($this->filters['order']) > 0) {
            foreach ($this->filters['order'] as $order) {
                $columnNumber = $order['column'] - 1;
                $columnName = key(array_slice($this->fields, $columnNumber, 1));
                $this->orders[] = [$columnName, $order['dir']];
            }
        }

        if (($this->filters['start'] || $this->filters['start'] == 0) && $this->filters['length']) {
            $this->skip = $this->filters['start'];
            $this->take = $this->filters['length'];

        }
    }

    private function setColumns()
    {
        $this->columns[] = $this->table . '.' . $this->pk;
        foreach ($this->fields as $key => $field) {
            if (isset($field['join']) && is_array($field['join'])) {
                $this->columns[] = $field['join']['show'] . ' AS ' . $key;
                if (!isset($this->joins[$field['join']['table2']])) {
                    $this->joins[$field['join']['table2']] = $field['join'];
                }
            } else {
                $this->columns[] = $this->table . '.' . $key;
            }
        }
    }

    private function convertDatas()
    {
        $manipulateData = [];

        if (count($this->datas) > 0) {
            foreach ($this->datas as $data) {
                $line = [];
                if ($this->multipleEvent) {
                    $line[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline table-line-selector" data-pk="' . $this->pk . '"><input name="' . $this->pk . '[]" type="checkbox" class="checkboxes" value="' . $data->{$this->pk} . '"/><span></span></label>';
                }

                foreach ($this->fields as $key => $field) {
                    if(isset($field['replace'])) {
                        $data->{$key} = str_replace($field['replace'][0], $field['replace'][1], $data->{$key});
                    }
                    if (isset($field['appearance'])) {
                        switch ($field['appearance']) {
                            case 'active':
                                if (isset($field['activeTexts'])) {
                                    $this->activeTexts = $field['activeTexts'];
                                }
                                if ($data->{$key} == '1') {
                                    $line[] = '<span class="badge badge-success tooltips" data-container="body" data-placement="left">' . $this->activeTexts[0] . '</span>';
                                } else if ($data->{$key} == '0') {
                                    $line[] = '<span class="badge badge-danger tooltips" data-container="body" data-placement="left">' . $this->activeTexts[1] . '</span>';
                                }
                                break;
                            case 'price':
                                if (!isset($field['priceShowType'])) {
                                    $field['priceShowType'] = 'normal';
                                }

                                switch ($field['priceShowType']) {
                                    case 'seperate':
                                        $line[] = priceSeperate($data->{$key});
                                        break;
                                    case 'normal':
                                        $line[] = price($data->{$key});
                                        break;
                                }
                                break;
                            case 'timestamp':
                                if (!isset($field['dateFormat'])) {
                                    $field['dateFormat'] = 'd.m.Y H:i';
                                }
                                $line[] = date($field['dateFormat'], strtotime($data->{$key}));
                                break;
                            case 'minified':
                                $line[] = substr(strip_tags($data->{$key}), 0, 50);
                                break;
                        }
                    } else if (isset($field['privateView'])) {
                        if ($text = $this->getPrivateView($field['privateView'], $data)) {
                            $line[] = $text;
                        } else {
                            $line[] = $data->{$key};
                        }
                    } else if ($field['type'] == 'image') {
                        if($data->{$key}) {

                            $image = File::where('Status', '1')->find($data->{$key});

                            $line[] = '<img src="' . url($image->Path) . '" class="img-datatable" width="150">';
                        } else {
                            $line[] = 'N/A';
                        }
                    } else {
                        $line[] = $data->{$key};
                    }
                }

                if ($this->transactions) {
                    $line[] = '';
                    $line[count($line) - 1] .= $this->setTransaction($data);
                }
                $manipulateData[] = $line;
            }
        }

        $this->response['data'] = $manipulateData;
        $this->response['recordsFiltered'] = $this->totalCount;
        $this->response['recordsTotal'] = $this->totalCount;
        $this->response['draw'] = $this->filters['draw'];

        return response()->json($this->response);
    }

    public function getEmptyData()
    {
        return response()->json(['data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0, 'draw' => $_POST['draw']]);
    }

    private function setTransaction($data)
    {
        $html = '';
        foreach ($this->transactions as $transaction) {
            if (!isset($transaction['color'])) {
                $transaction['color'] = 'default';
            }

            if ($href = $this->getPrivateView($transaction['href'], $data)) {
                $transaction['href'] = $href;
            }
//            <a class="btn btn-outline btn-info">Info</a>
            $html .= '<a href="' . $transaction['href'] . '" class="btn btn-outline btn-' . $transaction['color'] . '">' . ((isset($transaction['icon'])) ? '<i class="' . ((starts_with($transaction['icon'], 'fa-')) ? 'fa ' : '') . $transaction['icon'] . '"></i> ' : '') . ((isset($transaction['text'])) ? $transaction['text'] : '') . '</a>';

        }
        return $html;
    }

    private function getPrivateView($text, $data)
    {
        preg_match_all('/{#(.*?)#\}/i', $text, $array);

        if (isset($array[1]) && count($array[1]) > 0) {
            foreach ($array[1] as $item) {
                $text = str_replace('{#' . $item . '#}', $data->{$item}, $text);
            }
            return $text;
        } else {
            return false;
        }
    }

    private function setAction()
    {
        if (isset($this->filters['customActionName']) && isset($this->filters[$this->pk]) && is_array($this->filters[$this->pk])) {

            $keys = $this->filters[$this->pk];
            $customActionName = $this->filters['customActionName'];
            $exp = explode('|', $customActionName);
            $column = $exp[0];
            if (isset($exp[1])) {
                $value = $exp[1];
            } else {
                $value = NULL;
            }

            $actionStatus = DB::table($this->table);

            foreach ($keys as $index => $key) {
                if ($index == 0) {
                    $actionStatus = $actionStatus->where($this->pk, $key);
                } else {
                    $actionStatus = $actionStatus->orWhere($this->pk, $key);
                }
            }

            if ($actionStatus->update([$column => $value])) {
                $this->response['customActionStatus'] = 'OK';
                $this->response['customActionMessage'] = 'İşleminiz başarıyla gerçekleştirilmiştir.';
            } else {
                $this->response['customActionStatus'] = 'NO';
                $this->response['customActionMessage'] = 'İşleminiz gerçekleştirilirken hata meydana geldi.';
            }

        } else {
            echo 'no';
            exit;
        }
    }

}