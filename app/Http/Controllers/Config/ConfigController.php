<?php

namespace App\Http\Controllers\Config;

use App\Config;
use App\Http\Controllers\Controller;
use App\Http\Libraries\DataSeeker\DataSeeker;
use App\WelcomeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{

    private $formFields = [
        'autoTake' => ['text' => 'Otomatik Müşteri Aktarımı', 'form' => 'radio', 'values' => ['1' => 'Aktif', '0' => 'Pasif']],
        'workTime' => ['text' => 'Çalışanların Mesai Süresi (Saat)', 'form' => 'text'],
        'breakTime' => ['text' => 'Çalışanların Mola Süresi (Dakika)', 'form' => 'text'],
        'subject' => ['text' => 'Görüşmeye bağlanırken konu seçimi', 'form' => 'radio', 'values' => ['1' => 'Aktif', '0' => 'Pasif']],
    ];

    public function __construct()
    {
        $this->middleware('permission:config');
    }

    public function index()
    {
        return view('Config.index', ['configs' => Config::orderBy('id')->get(), 'formFields' => $this->formFields]);
    }

    public function update(Request $request)
    {
        foreach($this->formFields as $name => $field) {
            $config = Config::getConfig($name);
            if($config) {
                $config->value = $request->input($name);
                $config->save();
            }
        }
        return redirect(url('/configs'));
    }

}