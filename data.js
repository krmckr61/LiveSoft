function tableCreate() {
    var link = document.createElement('div');
    link.setAttribute('id', 'downloadLink');

    var csvHolder = document.createElement('div');
    csvHolder.setAttribute('id', 'csvHolder');
    var csv = document.createElement('div');
    csv.setAttribute('id', 'csv');

    var body = document.getElementsByTagName('body')[0];
    var tbl = document.createElement('table');
    tbl.style.width = '100%';
    tbl.setAttribute('border', '1');
    tbl.setAttribute('id', 'CsvTable');
    tbl.setAttribute('style', 'display:none');
    var tbdy = document.createElement('tbody');
    for (var i = 0; i < 1; i++) {
        var tr = document.createElement('tr');
        for (var j = 0; j < 2; j++) {
            var td = document.createElement('td');
            td.appendChild(document.createTextNode('\u0020'));
            tr.appendChild(td);
        }
        tbdy.appendChild(tr);
    }
    csvHolder.appendChild(csv);
    body.appendChild(csvHolder);
    body.appendChild(link);
    tbl.appendChild(tbdy);
    body.appendChild(tbl);
}
tableCreate();

function makeCSV() {
    var csv = "";
    $("table#CsvTable").find("tr").each(function () {
        var sep = "";
        $(this).find("td").each(function () {
            csv += sep + $(this).html();
            sep = ",";
        });
        csv += "\n";
    });
    $("#csv").text(csv);

    window.URL = window.URL || window.webkiURL;
    var blob = new Blob([csv]);
    var blobURL = window.URL.createObjectURL(blob);
    $("#downloadLink").html("");
    $("<a></a>").attr("href", blobURL).attr("download", "data.csv").text("Download Data").appendTo('#downloadLink');
}

function newRow(name, phone) {
    $("table#CsvTable").find("tr:last").clone().appendTo($("table#CsvTable"));
    $("table#CsvTable").find("tr:last td:first").html(name);
    $("table#CsvTable").find("tr:last td:last").html(phone);
}

function namePhone(rowNumber) {
    var row = $('.ui-datatable-selectable:nth-child(' + rowNumber + ')');
    var nameCol = $('.ui-datatable-selectable:nth-child(' + rowNumber + ') td:nth-child(11)');
    var name = nameCol.find('span').html();
    nameCol.click();
    var phone;
    setTimeout(function () {
        phone = getPhoneNumber();
        if(phone) {
            newRow(name, phone);
        }
    }, 1000);
}

function getPhoneNumber () {
    var phoneNumber = false;
    $("table#ctatt tbody tr").each( function(i) {
        var row = $("table#ctatt tbody tr:nth-child(" + (i + 1) + ")");
        var cell = row.find('td:first-child span').html();
        if(cell == 'Telefon' || cell == 'Sender No' || cell == 'Receiver No' || cell == 'Gönderici Tel' || cell == 'Alicinin Telefon Numarasi' || cell == 'Gondericinin Telefon Numarasi' || cell == 'Gonderenin Telefon Numarasi') {
            phoneNumber = row.find('td:last-child span').html();
        }
    });
    return phoneNumber;
}

function takeInfo() {
    namePhone(1);
    setTimeout(function () {
        namePhone(2);
        setTimeout(function () {
            namePhone(3);
            setTimeout(function () {
                namePhone(4);
                setTimeout(function () {
                    namePhone(5);
                    setTimeout(function () {
                        namePhone(6);
                        setTimeout(function () {
                            namePhone(7);
                            setTimeout(function () {
                                namePhone(8);
                                setTimeout(function () {
                                    namePhone(9);
                                    setTimeout(function () {
                                        namePhone(10);
                                        setTimeout(function () {
                                            namePhone(11);
                                            setTimeout(function () {
                                                namePhone(12);
                                                setTimeout(function () {
                                                    namePhone(13);
                                                    setTimeout(function () {
                                                        namePhone(14);
                                                        setTimeout(function () {
                                                            namePhone(15);
                                                            setTimeout(function () {
                                                                namePhone(16);
                                                                setTimeout(function () {
                                                                    namePhone(17);
                                                                    setTimeout(function () {
                                                                        namePhone(18);
                                                                        setTimeout(function () {
                                                                            namePhone(19);
                                                                            setTimeout(function () {
                                                                                namePhone(20);
                                                                            }, 1200);
                                                                        }, 1200);
                                                                    }, 1200);
                                                                }, 1200);
                                                            }, 1200);
                                                        }, 1200);
                                                    }, 1200);
                                                }, 1200);
                                            }, 1200);
                                        }, 1200);
                                    }, 1200);
                                }, 1200);
                            }, 1200);
                        }, 1200);
                    }, 1200);
                }, 1200);
            }, 1200);
        }, 1200);
    }, 1200);
}

//------------------------------------------------------------------------------------------

function take1000() {
    takeInfo();
    setTimeout(function () {
        $('.ui-paginator-next')[0].click();
        takeInfo();
        setTimeout(function () {
            $('.ui-paginator-next')[0].click();
            takeInfo();
            setTimeout(function () {
                $('.ui-paginator-next')[0].click();
                takeInfo();
                setTimeout(function () {
                    $('.ui-paginator-next')[0].click();
                    takeInfo();
                    setTimeout(function () {
                        $('.ui-paginator-next')[0].click();
                        takeInfo();
                        setTimeout(function () {
                            $('.ui-paginator-next')[0].click();
                            takeInfo();
                            setTimeout(function () {
                                $('.ui-paginator-next')[0].click();
                                takeInfo();
                                setTimeout(function () {
                                    $('.ui-paginator-next')[0].click();
                                    takeInfo();
                                    setTimeout(function () {
                                        $('.ui-paginator-next')[0].click();
                                        takeInfo();
                                        setTimeout(function () {
                                            $('.ui-paginator-next')[0].click();
                                            takeInfo();
                                            setTimeout(function () {
                                                $('.ui-paginator-next')[0].click();
                                                takeInfo();
                                                setTimeout(function () {
                                                    $('.ui-paginator-next')[0].click();
                                                    takeInfo();
                                                    setTimeout(function () {
                                                        $('.ui-paginator-next')[0].click();
                                                        takeInfo();
                                                        setTimeout(function () {
                                                            $('.ui-paginator-next')[0].click();
                                                            takeInfo();
                                                            setTimeout(function () {
                                                                $('.ui-paginator-next')[0].click();
                                                                takeInfo();
                                                                setTimeout(function () {
                                                                    $('.ui-paginator-next')[0].click();
                                                                    takeInfo();
                                                                    setTimeout(function () {
                                                                        $('.ui-paginator-next')[0].click();
                                                                        takeInfo();
                                                                        setTimeout(function () {
                                                                            $('.ui-paginator-next')[0].click();
                                                                            takeInfo();
                                                                            setTimeout(function () {
                                                                                $('.ui-paginator-next')[0].click();
                                                                                takeInfo();
                                                                                setTimeout(function () {
                                                                                    $('.ui-paginator-next')[0].click();
                                                                                    takeInfo();
                                                                                    setTimeout(function () {
                                                                                        $('.ui-paginator-next')[0].click();
                                                                                        takeInfo();
                                                                                        setTimeout(function () {
                                                                                            $('.ui-paginator-next')[0].click();
                                                                                            takeInfo();
                                                                                            setTimeout(function () {
                                                                                                $('.ui-paginator-next')[0].click();
                                                                                                takeInfo();
                                                                                                setTimeout(function () {
                                                                                                    $('.ui-paginator-next')[0].click();
                                                                                                    takeInfo();
                                                                                                    setTimeout(function () {
                                                                                                        $('.ui-paginator-next')[0].click();
                                                                                                        takeInfo();
                                                                                                        setTimeout(function () {
                                                                                                            $('.ui-paginator-next')[0].click();
                                                                                                            takeInfo();
                                                                                                            setTimeout(function () {
                                                                                                                $('.ui-paginator-next')[0].click();
                                                                                                                takeInfo();
                                                                                                                setTimeout(function () {
                                                                                                                    $('.ui-paginator-next')[0].click();
                                                                                                                    takeInfo();
                                                                                                                    setTimeout(function () {
                                                                                                                        $('.ui-paginator-next')[0].click();
                                                                                                                        takeInfo();
                                                                                                                        setTimeout(function () {
                                                                                                                            $('.ui-paginator-next')[0].click();
                                                                                                                            takeInfo();
                                                                                                                            setTimeout(function () {
                                                                                                                                $('.ui-paginator-next')[0].click();
                                                                                                                                takeInfo();
                                                                                                                                setTimeout(function () {
                                                                                                                                    $('.ui-paginator-next')[0].click();
                                                                                                                                    takeInfo();
                                                                                                                                    setTimeout(function () {
                                                                                                                                        $('.ui-paginator-next')[0].click();
                                                                                                                                        takeInfo();
                                                                                                                                        setTimeout(function () {
                                                                                                                                            $('.ui-paginator-next')[0].click();
                                                                                                                                            takeInfo();
                                                                                                                                            setTimeout(function () {
                                                                                                                                                $('.ui-paginator-next')[0].click();
                                                                                                                                                takeInfo();
                                                                                                                                                setTimeout(function () {
                                                                                                                                                    $('.ui-paginator-next')[0].click();
                                                                                                                                                    takeInfo();
                                                                                                                                                    setTimeout(function () {
                                                                                                                                                        $('.ui-paginator-next')[0].click();
                                                                                                                                                        takeInfo();
                                                                                                                                                        setTimeout(function () {
                                                                                                                                                            $('.ui-paginator-next')[0].click();
                                                                                                                                                            takeInfo();
                                                                                                                                                            setTimeout(function () {
                                                                                                                                                                $('.ui-paginator-next')[0].click();
                                                                                                                                                                takeInfo();
                                                                                                                                                                setTimeout(function () {
                                                                                                                                                                    $('.ui-paginator-next')[0].click();
                                                                                                                                                                    takeInfo();
                                                                                                                                                                    setTimeout(function () {
                                                                                                                                                                        $('.ui-paginator-next')[0].click();
                                                                                                                                                                        takeInfo();
                                                                                                                                                                        setTimeout(function () {
                                                                                                                                                                            $('.ui-paginator-next')[0].click();
                                                                                                                                                                            takeInfo();
                                                                                                                                                                            setTimeout(function () {
                                                                                                                                                                                $('.ui-paginator-next')[0].click();
                                                                                                                                                                                takeInfo();
                                                                                                                                                                                setTimeout(function () {
                                                                                                                                                                                    $('.ui-paginator-next')[0].click();
                                                                                                                                                                                    takeInfo();
                                                                                                                                                                                    setTimeout(function () {
                                                                                                                                                                                        $('.ui-paginator-next')[0].click();
                                                                                                                                                                                        takeInfo();
                                                                                                                                                                                        setTimeout(function () {
                                                                                                                                                                                            $('.ui-paginator-next')[0].click();
                                                                                                                                                                                            takeInfo();
                                                                                                                                                                                            setTimeout(function () {
                                                                                                                                                                                                $('.ui-paginator-next')[0].click();
                                                                                                                                                                                                takeInfo();
                                                                                                                                                                                                setTimeout(function () {
                                                                                                                                                                                                    $('.ui-paginator-next')[0].click();
                                                                                                                                                                                                    takeInfo();
                                                                                                                                                                                                    setTimeout(function () {
                                                                                                                                                                                                        $('.ui-paginator-next')[0].click();
                                                                                                                                                                                                        takeInfo();
                                                                                                                                                                                                    }, 35000);
                                                                                                                                                                                                }, 35000);
                                                                                                                                                                                            }, 35000);
                                                                                                                                                                                        }, 35000);
                                                                                                                                                                                    }, 35000);
                                                                                                                                                                                }, 35000);
                                                                                                                                                                            }, 35000);
                                                                                                                                                                        }, 35000);
                                                                                                                                                                    }, 35000);
                                                                                                                                                                }, 35000);
                                                                                                                                                            }, 35000);
                                                                                                                                                        }, 35000);
                                                                                                                                                    }, 35000);
                                                                                                                                                }, 35000);
                                                                                                                                            }, 35000);
                                                                                                                                        }, 35000);
                                                                                                                                    }, 35000);
                                                                                                                                }, 35000);
                                                                                                                            }, 35000);
                                                                                                                        }, 35000);
                                                                                                                    }, 35000);
                                                                                                                }, 35000);
                                                                                                            }, 35000);
                                                                                                        }, 35000);
                                                                                                    }, 35000);
                                                                                                }, 35000);
                                                                                            }, 35000);
                                                                                        }, 35000);
                                                                                    }, 35000);
                                                                                }, 35000);
                                                                            }, 35000);
                                                                        }, 35000);
                                                                    }, 35000);
                                                                }, 35000);
                                                            }, 35000);
                                                        }, 35000);
                                                    }, 35000);
                                                }, 35000);
                                            }, 35000);
                                        }, 35000);
                                    }, 35000);
                                }, 35000);
                            }, 35000);
                        }, 35000);
                    }, 35000);
                }, 35000);
            }, 35000);
        }, 35000);
    }, 35000);
}


