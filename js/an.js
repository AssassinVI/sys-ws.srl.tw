//=========================================== 撈取資料 ====================================

//使用者性別
function ajax_sex(an, an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'sex',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');

            //-- 總人數 --
            var total_num = $('#all_user').html();
            var none_num = parseInt(total_num) - parseInt(data_num[0]) - parseInt(data_num[1]);
            data_name.push('未知');
            data_num.push(none_num);


            //-- 時間區間2 --
            if (an_data[2] != undefined) {

                data_name = ['x', '女性', '男性'];
                if (an_s_date == '') {
                    data_num.splice(0, 0, '使用人數');
                }
                else {
                    data_num.splice(0, 0, '時間區間1');
                }

                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');

                data_c_name.splice(0, 0, 'x');
                data_c_num.splice(0, 0, '時間區間2');
            }



            if (an_data[1] == '') {
                an.load({
                    unload: true
                });
            }
            else {

                an.unload();

                //-- 時間區間2 --
                if (an_data[2] != undefined) {

                    var columns_arr = [data_name, data_num];
                    columns_arr.push(data_c_num);

                    setTimeout(() => {
                        an.load({
                            columns: columns_arr,
                            type: 'bar',
                            colors: {
                                '時間區間1': '#2196f3',
                                '時間區間2': '#ff6258',
                            }
                        });

                        an.legend.show();

                    }, 500);
                }
                else {

                    var columns_arr = [
                        ['女性', data_num[0]],
                        ['男性', data_num[1]],
                        ['未知', data_num[2]],
                        ['x', '女性', '男性', '未知']
                    ];

                    setTimeout(() => {
                        an.load({
                            columns: columns_arr,
                            type: 'pie'
                        });
                        an.legend.hide();
                        ch_an_legend(an, '.sex_legend');

                    }, 500);

                }


            }


        }
    });
}



//回訪率
function ajax_userType_per(an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'userType',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            
            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');

            var avg_data = (parseInt(data_num[1]) / (parseInt(data_num[0]) + parseInt(data_num[1])))*100;
            avg_data = Math.round(avg_data);
            console.log(Math.round(avg_data));

            $('#back_user').html(avg_data+'%');

            //-- 時間區間2 --
            if (an_data[2] != undefined) {

                // data_name = ['x', '新訪者', '回訪者'];
                // if (an_s_date == '') {
                //     data_num.splice(0, 0, '使用人數');
                // }
                // else {
                //     data_num.splice(0, 0, '時間區間1');
                // }

                // var data_c_name = an_data[2].split(',');
                // var data_c_num = an_data[3].split(',');

                // data_c_name.splice(0, 0, 'x');
                // data_c_num.splice(0, 0, '時間區間2');
            }



            if (an_data[1] == '') {
                // an.load({
                //     unload: true
                // });
            }
            else {

               // an.unload();

                //-- 時間區間2 --
                if (an_data[2] != undefined) {

                    // var columns_arr = [data_name, data_num];
                    // columns_arr.push(data_c_num);

                    // setTimeout(() => {
                    //     an.load({
                    //         columns: columns_arr,
                    //         type: 'bar',
                    //         colors: {
                    //             '時間區間1': '#2196f3',
                    //             '時間區間2': '#ff6258',
                    //         }
                    //     });

                    //     an.legend.show();

                    // }, 500);
                }
                else {

                    // var columns_arr = [
                    //     ['新訪者', data_num[0]],
                    //     ['回訪者', data_num[1]],
                    //     ['x', '新訪者', '回訪者']
                    // ];

                    // setTimeout(() => {
                    //     an.load({
                    //         columns: columns_arr,
                    //         type: 'donut'
                    //     });
                    //     an.legend.hide();
                    //     ch_an_legend(an, '.userType_legend');

                    // }, 500);
                }
            }
        }
    });
}


//忠誠度
function ajax_loyalty(an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'loyalty',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {


            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');
            var data_num_total=0;

            for (let i = 0; i < data_num.length; i++) {
                data_num_total += parseInt(data_num[i]);
            }

            var avg_data = (parseInt(data_num_total) / parseInt(an_data[2]))*100;
            avg_data = Math.round(avg_data);
            

            $('#loyalty').html(avg_data + '%');

            //-- 時間區間2 --
            if (an_data[2] != undefined) {

                // data_name = ['x', '新訪者', '回訪者'];
                // if (an_s_date == '') {
                //     data_num.splice(0, 0, '使用人數');
                // }
                // else {
                //     data_num.splice(0, 0, '時間區間1');
                // }

                // var data_c_name = an_data[2].split(',');
                // var data_c_num = an_data[3].split(',');

                // data_c_name.splice(0, 0, 'x');
                // data_c_num.splice(0, 0, '時間區間2');
            }



            if (an_data[1] == '') {
                // an.load({
                //     unload: true
                // });
            }
            else {

                // an.unload();

                //-- 時間區間2 --
                if (an_data[2] != undefined) {

                    // var columns_arr = [data_name, data_num];
                    // columns_arr.push(data_c_num);

                    // setTimeout(() => {
                    //     an.load({
                    //         columns: columns_arr,
                    //         type: 'bar',
                    //         colors: {
                    //             '時間區間1': '#2196f3',
                    //             '時間區間2': '#ff6258',
                    //         }
                    //     });

                    //     an.legend.show();

                    // }, 500);
                }
                else {

                    // var columns_arr = [
                    //     ['新訪者', data_num[0]],
                    //     ['回訪者', data_num[1]],
                    //     ['x', '新訪者', '回訪者']
                    // ];

                    // setTimeout(() => {
                    //     an.load({
                    //         columns: columns_arr,
                    //         type: 'donut'
                    //     });
                    //     an.legend.hide();
                    //     ch_an_legend(an, '.userType_legend');

                    // }, 500);
                }
            }
        }
    });
}



//新訪者/回訪者
function ajax_userType(an, an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'userType',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');


            //-- 時間區間2 --
            if (an_data[2] != undefined) {

                data_name = ['x', '新訪者', '回訪者'];
                if (an_s_date == '') {
                    data_num.splice(0, 0, '使用人數');
                }
                else {
                    data_num.splice(0, 0, '時間區間1');
                }

                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');

                data_c_name.splice(0, 0, 'x');
                data_c_num.splice(0, 0, '時間區間2');
            }



            if (an_data[1] == '') {
                an.load({
                    unload: true
                });
            }
            else {

                an.unload();

                //-- 時間區間2 --
                if (an_data[2] != undefined) {

                    var columns_arr = [data_name, data_num];
                    columns_arr.push(data_c_num);

                    setTimeout(() => {
                        an.load({
                            columns: columns_arr,
                            type: 'bar',
                            colors: {
                                '時間區間1': '#2196f3',
                                '時間區間2': '#ff6258',
                            }
                        });

                        an.legend.show();

                    }, 500);
                }
                else {

                    var columns_arr = [
                        ['新訪者', data_num[0]],
                        ['回訪者', data_num[1]],
                        ['x', '新訪者', '回訪者']
                    ];

                    setTimeout(() => {
                        an.load({
                            columns: columns_arr,
                            type: 'donut'
                        });
                        an.legend.hide();
                        ch_an_legend(an, '.userType_legend');

                    }, 500);
                }
            }
        }
    });
}


//回訪次數(人數)
function ajax_userCount(an, an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'userCount',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');

            data_name.splice(0, 0, 'x');
            if (an_s_date == '') {
                data_num.splice(0, 0, '使用人數');
            }
            else {
                data_num.splice(0, 0, '時間區間1');
            }


            //-- 時間區間2 --
            if (an_data[2] != undefined) {
                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');

                data_c_name.splice(0, 0, 'x');
                data_c_num.splice(0, 0, '時間區間2');
            }


            if (an_data[1] == '') {
                an.load({
                    unload: true,
                    colors: {
                        '使用人數': '#2196f3'
                    }
                });
            }
            else {

                var columns_arr = [data_name, data_num];

                //-- 時間區間2 --
                if (an_data[2] != undefined) {
                    columns_arr.push(data_c_num);
                }

                an.unload();

                setTimeout(() => {
                    an.load({
                        columns: columns_arr,
                        colors: {
                            '時間區間1': '#2196f3',
                            '時間區間2': '#ff6258',
                        }
                    });
                }, 500);

            }

        }
    });
}



//使用者年齡
function ajax_old(an, an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'old',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {
            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');

            data_name.splice(0, 0, 'x');
            if (an_s_date == '') {
                data_num.splice(0, 0, '使用人數');
            }
            else {
                data_num.splice(0, 0, '時間區間1');
            }


            //-- 時間區間2 --
            if (an_data[2] != undefined) {
                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');

                data_c_name.splice(0, 0, 'x');
                data_c_num.splice(0, 0, '時間區間2');
            }


            if (an_data[1] == '') {
                an.load({
                    unload: true,
                    colors: {
                        '使用人數': '#2196f3'
                    }
                });
            }
            else {

                var columns_arr = [data_name, data_num];

                //-- 時間區間2 --
                if (an_data[2] != undefined) {
                    columns_arr.push(data_c_num);
                }

                an.unload();

                setTimeout(() => {
                    an.load({
                        columns: columns_arr,
                        colors: {
                            '時間區間1': '#2196f3',
                            '時間區間2': '#ff6258',
                        }
                    });
                }, 500);

            }

        }
    });
}




//使用媒體
function ajax_media(an, an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'media',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            var an_data = data.split('|');
            //var data_name=an_data[0].split(',');
            var data_num = an_data[1].split(',');
            //data_name.splice(0,0,'x');
            if (an_s_date == '') {
                data_num.splice(0, 0, '使用人數');
            }
            else {
                data_num.splice(0, 0, '時間區間1');
            }


            //-- 時間區間2 --
            if (an_data[2] != undefined) {

                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');

                data_c_name.splice(0, 0, 'x');
                data_c_num.splice(0, 0, '時間區間2');
            }

            if (an_data[1] == '') {
                an.load({
                    unload: true
                });
            }
            else {

                var columns_arr = [['x', '桌機', '手機', '平板'], data_num];

                //-- 時間區間2 --
                if (an_data[2] != undefined) {
                    columns_arr.push(data_c_num);
                }

                an.unload();
                setTimeout(() => {
                    an.load({
                        columns: columns_arr,
                        colors: {
                            '時間區間1': '#2196f3',
                            '時間區間2': '#ff6258',
                        }
                    });
                }, 500);

            }
        }
    });
}

//使用功能鈕
function ajax_tool_btn(an, an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'tool_btn',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {


            an.unload();

            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');

            //-- 時間區間2 --
            if (an_data[2] != undefined) {

                data_name.splice(0, 0, 'x');
                if (an_s_date == '') {
                    data_num.splice(0, 0, '使用人數');
                }
                else {
                    data_num.splice(0, 0, '時間區間1');
                }

                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');

                data_c_name.splice(0, 0, 'x');
                data_c_num.splice(0, 0, '時間區間2');
            }


            if (an_data[1] == '') {
                an.load({
                    unload: true
                });
            }
            else {
                var data_all = [];
                var data_x = ['x'];
                var data_num_new = data_name.length > 5 ? 5 : data_name.length;
                for (var i = 0; i < data_num_new; i++) {
                    data_all.push([data_name[i], data_num[i]]);
                    data_x.push(data_name[i]);
                }
                data_all.push(data_x);


                //-- 時間區間2 --
                if (an_data[2] != undefined) {

                    var columns_arr = [data_name, data_num];
                    columns_arr.push(data_c_num);

                    //-- 限制5個 --
                    var data_arr_length = 6;
                    for (let i = 0; i < 3; i++) {
                        var arr_length = columns_arr[i].length;
                        var arr_cut_5 = parseInt(arr_length) - data_arr_length;
                        if (arr_cut_5 > 0) {
                            columns_arr[i].splice(data_arr_length, arr_cut_5);
                        }
                    }

                    // for (let i = 0; i < data_c_name.length; i++) {
                    //   var data_index=data_name.indexOf(data_c_name[i]);
                    //   if(data_index!=-1){
                    //      data_all[data_index][2]=data_c_num[i];
                    //   }
                    //   else{
                    //     data_all.push([data_c_name[i], 0, data_c_num[i]]);
                    //   }
                    // }

                    // var data_x=['x','時間區間1', '時間區間2'];
                    // data_all.push(data_x);

                    //console.log(columns_arr);


                    setTimeout(() => {
                        an.load({
                            columns: columns_arr,
                            colors: {
                                '時間區間1': '#2196f3',
                                '時間區間2': '#ff6258',
                            },
                            type: 'bar'
                        });

                        an.legend.show();
                    }, 500);
                }
                else {

                    setTimeout(() => {
                        an.load({
                            columns: data_all,
                            type: 'donut'
                        });
                        an.legend.hide();
                        ch_an_legend(an, '.tool_btn_legend');
                    }, 500);
                    
                    //-- 改顏色 --
                    ch_an_color(an, data_name);
                }
                //console.log(data_all);
            }

        }
    });
}



//流量來源
function ajax_src_num(an, an_s_date, an_e_date, com_s_date, com_e_date) {

    if (an_s_date != '') {
        //-- 隱藏月流量 --
        // $('#all_src_div').removeClass('col-lg-6');
        // $('#all_src_div').addClass('col-lg-12');
        $('#month_src_div').css('display', 'none');
    }
    else {
        //-- 顯示月流量 --
        // $('#all_src_div').addClass('col-lg-6');
        // $('#all_src_div').removeClass('col-lg-12');
        $('#month_src_div').css('display', 'block');
    }

    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'src_num',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');

            //-- 時間區間2 --
            if (an_data[2] != undefined) {


                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');

            }

            // console.log(data_name);
            // console.log(data_num);
            // console.log(data_c_name);
            // console.log(data_c_num);

            an.unload();

            //-- 無資料 --
            if (an_data[1] == '') {
                an.load({
                    unload: true
                });
            }
            //-- 有資料 --
            else {

                var show = 1;
                var data_all = [];
                var data_x = ['x'];
                var total = 0;


                data_number = data_name.length > 10 ? 10 : data_name.length;
                for (var i = 0; i < data_number; i++) {

                    var new_data = src_ch(data_name[i], data_num[i]);

                    if (new_data[0] !== undefined) {
                        data_all.push(new_data);
                        data_x.push(new_data[0]);
                    }
                }

                data_all.push(data_x);




                //-- 時間區間2 --
                if (an_data[2] != undefined) {

                    var data_all = [];
                    var data_x = [];

                    //-- 時間區間1 --
                    var data_name_new = [];
                    var data_number = data_name.length > 5 ? 5 : data_name.length;

                    for (var i = 0; i < data_number; i++) {
                        data_name_new.push(data_name[i]);
                        var new_data = src_ch(data_name[i], data_num[i]);

                        if (new_data[0] !== undefined) {
                            data_all.push(new_data[1]);
                            data_x.push(new_data[0]);
                        }
                    }


                    var data_c_all = [];

                    //-- 時間區間1無而時間區間2有的來源 --
                    var data_c_sp = [];
                    var data_c_sp_name = [];
                    var data_c_sp_num = [];

                    for (let i = 0; i < data_c_name.length; i++) {
                        var data_index = data_name_new.indexOf(data_c_name[i]);
                        if (data_index != -1) {
                            data_c_all[data_index] = data_c_num[i];
                        }
                        //-- 時間區間1無而時間區間2有的來源 --
                        else {
                            var new_c_data = src_ch(data_c_name[i], data_c_num[i]);
                            if (new_c_data[0] !== undefined) {
                                data_c_sp.push(new_c_data[1]);
                                data_c_sp_name.push(new_c_data[0]);
                                data_c_sp_num.push(i);
                            }
                        }
                    }

                    //-- 時間區間2(空值補0) --
                    for (let i = 0; i < data_c_all.length; i++) {
                        data_c_all[i] = data_c_all[i] == undefined ? 0 : data_c_all[i];
                    }

                    // console.log(data_c_sp_name);
                    // console.log(data_c_sp_num);

                    //-- 時間區間1無而時間區間2有的來源 (套入) --
                    for (let j = 0; j < data_c_sp.length; j++) {

                        if (data_name.indexOf(data_c_sp_name[j]) != -1) {
                            var data_inedx = data_name.indexOf(data_c_sp_name[j]);
                            data_all.splice(data_c_sp_num[j], 0, data_num[data_inedx]);
                        }
                        else {
                            data_all.splice(data_c_sp_num[j], 0, 0);
                        }

                        data_c_all.splice(data_c_sp_num[j], 0, data_c_sp[j]);
                        data_x.splice(data_c_sp_num[j], 0, data_c_sp_name[j]);
                    }

                    data_x.splice(0, 0, 'x');
                    data_all.splice(0, 0, '時間區間1');
                    data_c_all.splice(0, 0, '時間區間2');

                    //-- 調整圖表顯示來源種類 --
                    data_x = data_x.slice(0, 6);
                    // console.log(data_all);
                    // console.log(data_c_all);


                    var data_all_all = [];
                    data_all_all.push(data_x);
                    data_all_all.push(data_all);
                    data_all_all.push(data_c_all);


                    setTimeout(() => {
                        an.load({
                            columns: data_all_all,
                            colors: {
                                '時間區間1': '#2196f3',
                                '時間區間2': '#ff6258',
                            },
                            type: 'bar'
                        });
                        an.legend.show();
                    }, 500);
                }
                else {
                    setTimeout(() => {
                        an.load({
                            columns: data_all,
                            type: 'pie'
                        });
                        an.legend.hide();
                        ch_an_legend(an, '.src_num_legend');
                    }, 500);

                    //-- 改顏色 --
                    ch_an_color(an, data_name);
                }

            }

            // console.log(data);

        }
    });
}




//月流量來源
function ajax_month_src_num(an) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'month_src_num',
            Tb_index: Tb_index
        },
        success: function (data) {

            console.log(data);
            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');
            var show = 1;
            var data_all = [];
            var total = 0;
            //var other_total=0;

            // for (var i = 0; i < data_num.length; i++) {
            //   total+=parseInt(data_num[i]);
            // }
            //   total=Math.round(total/data_num.length);
            //total=20;
            var new_data_num = data_name.length > 10 ? 10 : data_name.length;
            for (var i = 0; i < new_data_num; i++) {

                var new_data = src_ch(data_name[i], data_num[i]);

                if (new_data[0] !== undefined) {
                    data_all.push(new_data);
                }
            }

            setTimeout(() => {
                an.load({
                    columns: data_all
                });
                ch_an_legend(an, '.month_src_num_legend'); 
            }, 500);
            
            //-- 改顏色 --
            ch_an_color(an, data_name);
        }
    });
}



//流量來源 詳細資料
function ajax_src_num_d(an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'src_num',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            $('.src_tb #com_tb').html('');

            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');
            var show = 1;


            //-- 時間區間2 --
            if (an_data[2] != undefined) {
                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');
            }
            else {
                var data_c_name = [];
            }


            if (data_c_name.length >1 ) {
                $('#all_src_tb .num_name1').html('時間區間1');
                if ($('#all_src_tb .num_name2').length < 1) {
                    $('#all_src_tb thead tr').append('<th class="num_name2 text-right">時間區間2</th>');
                }

                var data_total = data_c_name.length;
            }
            else {
                $('#all_src_tb .num_name1').html('人數');
                if ($('#all_src_tb .num_name2').length > 0) {
                    $('#all_src_tb .num_name2').remove();
                }
                var data_total = data_name.length;
            }


            for (var i = 0; i < data_total; i++) {


                //-- 比較 --
                if (an_data[2] != undefined) {
                    var new_c_data = src_ch(data_c_name[i], data_c_num[i]);
                }


                //-- 比較 --
                if (an_data[2] != undefined) {

                    var data_index = data_name.length <= data_c_name.length ? data_name.indexOf(data_c_name[i]) : data_c_name.indexOf(data_name[i]);
                    var data_all_name = data_name.length <= data_c_name.length ? data_c_name[i] : data_name[i];
                    var data_all_num = data_name.length <= data_c_name.length ? data_num[data_index] : data_num[i];
                    var data_all_c_num = data_name.length <= data_c_name.length ? data_c_num[i] : data_c_num[data_index];

                    if (data_all_num > 10 || data_all_c_num > 10) {
                        if (data_index != -1) {
                            var new_data = src_ch(data_all_name, data_all_num);

                            if (new_data[0] != undefined) {
                                var txt = '<tr><td>' + new_data[0] + '</td><td class="text-right">' + new_data[1] + '</td><td class="text-right">' + data_all_c_num + '</td></tr>';
                                $('.src_tb #com_tb').append(txt);
                            }
                        }
                        else {
                            var new_data = src_ch(data_all_name, data_all_num);

                            if (new_data[0] != undefined) {
                                var new_data_num1 = new_data[1] == undefined ? '0' : new_data[1];
                                var new_data_num2 = data_all_c_num == undefined ? '0' : data_all_c_num;
                                var txt = '<tr><td>' + new_data[0] + '</td><td class="text-right">' + new_data_num1 + '</td><td class="text-right">' + new_data_num2 + '</td></tr>';
                                $('.src_tb #com_tb').append(txt);
                            }
                        }
                    }
                }
                else {

                    var new_data = src_ch(data_name[i], data_num[i]);
                    if (data_num[i] > 10 && new_data[0] != undefined) {
                        var new_data_num1 = new_data[1] == undefined ? '0' : new_data[1];
                        var txt = '<tr><td>' + new_data[0] + '</td><td class="text-right">' + new_data_num1 + '</td></tr>';
                        $('.src_tb #com_tb').append(txt);
                    }
                }

            }
        }
    });
}


//月流量來源 詳細資料
function ajax_month_src_num_d() {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'month_src_num',
            Tb_index: Tb_index
        },
        success: function (data) {
            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');
            var show = 1;

            for (var i = 0; i < data_name.length; i++) {


                if (data_num[i] > 10) {

                    var new_data = src_ch(data_name[i], data_num[i]);

                    if (new_data[0] != undefined) {
                        var txt = '<tr><td>' + new_data[0] + '</td><td class="text-right">' + new_data[1] + '</td></tr>';
                        $('.month_src_tb #com_tb').append(txt);
                    }

                }
            }
        }
    });
}


//地區使用人數
function ajax_city(an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'city',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            // console.log(data);
            $('#city #com_tb').html('');
            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');

            //-- 時間區間2 --
            if (an_data[2] != undefined) {
                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');

                $('#city #title_tb').html('<tr><th>地名</th><th class="text-right">時間區間1</th><th class="text-right">時間區間2</th></tr>');
            }
            else {
                var data_c_name = [];
                $('#city #title_tb').html('<tr><th>地名</th><th class="text-right">時間區間1</th></tr>');
            }


            for (var i = 0; i < data_name.length; i++) {

                //-- 時間區間2 --
                if (an_data[2] != undefined && data_name.indexOf(data_c_name[i]) != -1) {
                    var txt = '<tr><td>' + data_name[i].substr(0, 3) + '</td><td class="text-right">' + data_num[i] + '</td><td class="text-right">' + data_c_num[i] + '</td></tr>';
                }
                else {
                    var txt = '<tr><td>' + data_name[i].substr(0, 3) + '</td><td class="text-right">' + data_num[i] + '</td></tr>';
                }

                $('#city #com_tb').append(txt);
            }
        }
    });
}


//齡層平均停留網站時間
function ajax_timeOnSite(an, an_s_date, an_e_date, com_s_date, com_e_date) {

    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'timeOnSite',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {
            console.log(data);
            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');

            //-- 時間區間2 --
            if (an_data[2] != undefined) {
                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');
            }

            data_name.splice(0, 0, 'x');
            if (an_s_date == '') {
                data_num.splice(0, 0, '停留時間(比率)');
            } else {
                data_num.splice(0, 0, '時間區間1');
            }


            //-- 時間區間2 --
            if (an_data[2] != undefined) {

                data_c_name.splice(0, 0, 'x');
                data_c_num.splice(0, 0, '時間區間2');
            }

            if (an_data[1] == '') {
                an.load({
                    unload: true,
                    colors: {
                        '停留時間(比率)': '#2196f3'
                    }
                });
            }
            else {

                var columns_arr = [data_name, data_num];
                an.unload();

                //-- 時間區間2 --
                if (an_data[2] != undefined) {
                    columns_arr.push(data_c_num);

                    setTimeout(function () {
                        an.load({
                            columns: columns_arr,
                            colors: {
                                '時間區間1': '#2196f3',
                                '時間區間2': '#ff6258',
                            }
                        });
                    }, 500);
                }
                else {
                    setTimeout(function () {
                        an.load({
                            columns: columns_arr,
                            colors: {
                                '時間區間1': '#2196f3'
                            }
                        });
                    }, 500);
                }
            }


        }
    });
}


//每日使用人數
function ajax_data_use(an, an_s_date, an_e_date, com_s_date, com_e_date) {
    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'data_use',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            var an_data = data.split('|');
            var data_name = an_data[0].split(',');
            var data_num = an_data[1].split(',');

            //-- 時間區間2 --
            if (an_data[2] != undefined) {
                var data_c_name = an_data[2].split(',');
                var data_c_num = an_data[3].split(',');
            }


            if (data_name.length > 30 && (an_s_date == undefined || an_s_date == '')) {
                var d30_s_num = parseInt(data_name.length) - 30;
                var d30_e_num = parseInt(data_name.length);
                // console.log('s:'+d30_s_num+',e:'+d30_e_num);
                var d30_data_name = [];
                var d30_data_num = [];
                for (var i = d30_s_num; i < d30_e_num; i++) {
                    d30_data_name.push(data_name[i]);
                    d30_data_num.push(data_num[i]);
                }
                d30_data_name.splice(0, 0, 'x');
                d30_data_num.splice(0, 0, '使用人數');

                an.unload();
                setTimeout(function () {
                    an.load({
                        columns: [d30_data_name, d30_data_num],
                        colors:{
                            '使用人數': '#2196f3'
                        }
                    });

                    an.legend.hide();
                    ch_an_time_legend(an, '.date_use_legend');
                }, 500);


            }
            else {
                data_name.splice(0, 0, 'x');
                data_num.splice(0, 0, '時間區間1-使用人數');

                //-- 時間區間2 --
                if (an_data[2] != undefined) {
                    data_c_name.splice(0, 0, 'x');
                    data_c_num.splice(0, 0, '時間區間2-使用人數');
                }

                if (an_data[1] == '') {
                    an.load({
                        unload: true,
                        colors: {
                            '時間區間1-使用人數': '#2196f3'
                        }
                    });
                }
                else {

                    var columns_arr = [data_name, data_num];
                    an.unload();
                    //-- 時間區間2 --
                    if (an_data[2] != undefined) {

                        columns_arr.push(data_c_num);

                        setTimeout(function () {

                            an.load({
                                columns: columns_arr,
                                colors: {
                                    '時間區間1-使用人數': '#2196f3',
                                    '時間區間2-使用人數': '#ff6258',
                                }
                            });

                            an.legend.hide();
                            ch_an_time_legend(an, '.date_use_legend');
                        }, 500);
                    }
                    else {
                        setTimeout(function () {

                            an.load({
                                columns: columns_arr,
                                colors: {
                                    '時間區間1-使用人數': '#2196f3'
                                }
                            });
                            an.legend.hide();
                            ch_an_time_legend(an, '.date_use_legend');
                        }, 500);
                    }

                }

            }
        }
    });
}


//-- 一週||最大使用人數 --
function ajax_max_week_user(an, an_s_date, an_e_date, com_s_date, com_e_date) {

    an.xgrids.remove({ class: 'big_num' });

    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'max_user',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            if (an_s_date == '') {
                $('#max_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 一周人數');
                $('#max_user').html(data + '人');
            }
            else {

                var user_data = data.split(',');

                //-- 電腦 --
                if ($(window).width() > 768) {
                    $('#max_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 時間區間1-最大瀏覽人數 <small>' + user_data[1] + '</small>');
                    $('#max_user').html(user_data[0] + '人');
                }
                //-- 手機 --
                else {
                    $('#max_user').prev().find('h5').html('<i class="fa fa-user-o"></i>最大人數');
                    $('#max_user').html('<small>' + user_data[1] + '</small>' + user_data[0] + '人');
                    $('#user_max_d_div div:nth-child(2) p').html(toCurrency(user_data[0]));
                    $('#user_max_d_div div:nth-child(2) span').html(user_data[1]);
                }

                if (com_s_date == undefined || com_s_date == '') {
                    var big_date = user_data[1].split('-');
                    setTimeout(function () {
                        an.xgrids.add([{ value: big_date[0] + big_date[1] + big_date[2], text: '時間區間1-最大瀏覽人數', position: 'start', class: 'big_num' }]);
                    }, 1000);
                }
                else {

                    //-- 電腦 --
                    if ($(window).width() > 768) {
                        $('#user_data_c_div #max_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 時間區間2-最大瀏覽人數 <small>' + user_data[3] + '</small>');
                        $('#user_data_c_div #max_user').html(user_data[2] + '人');
                    }
                    //-- 手機 --
                    else {
                        // $('#user_data_c_div #max_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 時間區間2-最大瀏覽人數');
                        // $('#user_data_c_div #max_user').html('<small>'+user_data[3]+'</small>'+user_data[2]+'人');
                        $('#user_max_d_div div:nth-child(3) p').html(toCurrency(user_data[2]));
                        $('#user_max_d_div div:nth-child(3) span').html(user_data[3]);
                    }
                }




            }
        }
    });
}



//-- 一月||最小使用人數 --
function ajax_min_month_user(an, an_s_date, an_e_date, com_s_date, com_e_date) {

    an.xgrids.remove({ class: 'small_num' });

    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'min_user',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {

            //  console.log(data);
            if (an_s_date == '') {
                $('#min_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 一個月人數');
                $('#min_user').html(data + '人');
            }
            else {

                var user_data = data.split(',');

                //-- 電腦 --
                if ($(window).width() > 768) {
                    $('#min_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 時間區間1-最小瀏覽人數 <small>' + user_data[1] + '</small>');
                    $('#min_user').html(user_data[0] + '人');
                }
                //-- 手機 --
                else {
                    $('#min_user').prev().find('h5').html('<i class="fa fa-user-o"></i>最小人數');
                    $('#min_user').html('<small>' + user_data[1] + '</small>' + user_data[0] + '人');
                    $('#user_min_d_div div:nth-child(2) p').html(toCurrency(user_data[0]));
                    $('#user_min_d_div div:nth-child(2) span').html(user_data[1]);
                }


                if (com_s_date == undefined || com_s_date == '') {
                    var big_date = user_data[1].split('-');
                    setTimeout(function () {
                        an.xgrids.add([{ value: big_date[0] + big_date[1] + big_date[2], text: '時間區間1-最小瀏覽人數', class: 'small_num' }]);
                    }, 1000);
                }
                else {

                    //-- 電腦 --
                    if ($(window).width() > 768) {
                        $('#user_data_c_div #min_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 時間區間2-最小瀏覽人數 <small>' + user_data[3] + '</small>');
                        $('#user_data_c_div #min_user').html(user_data[2] + '人');
                    }
                    //-- 手機 --
                    else {
                        // $('#user_data_c_div #min_user').prev().find('h5').html('<i class="fa fa-user-o"></i> 時間區間2-最小瀏覽人數');
                        // $('#user_data_c_div #min_user').html('<small>'+user_data[3]+'</small>'+user_data[2]+'人');
                        $('#user_min_d_div div:nth-child(3) p').html(toCurrency(user_data[2]));
                        $('#user_min_d_div div:nth-child(3) span').html(user_data[3]);
                    }
                }

            }
        }
    });
}



//-- 總使用人數 --
function ajax_all_user(an_s_date, an_e_date, com_s_date, com_e_date) {

    $.ajax({
        url: 'analytics_ajax.php',
        type: 'POST',
        data: {
            type: 'all_user',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            com_StartDate: com_s_date,
            com_EndDate: com_e_date
        },
        success: function (data) {
            if (com_s_date != '' && com_s_date != undefined) {
                var data_arr = data.split(',');

                if ($(window).width() > 768) {
                    $('.all_user_h5 h5').html('<i class="fa fa-user-o"></i> 時間區間1-總瀏覽人數');
                    $('#user_data_c_div .all_user_h5 h5').html('<i class="fa fa-user-o"></i> 時間區間2-總瀏覽人數');
                    $('#all_user').html(data_arr[0] + '人');
                    $('#user_data_c_div #all_user').html(data_arr[1] + '人');
                }
                else {

                    $('#user_all_d_div div:nth-child(2) p').html(toCurrency(data_arr[0]));
                    $('#user_all_d_div div:nth-child(3) p').html(toCurrency(data_arr[1]));
                }
            }
            else {
                $('.all_user_h5 h5').html('<i class="fa fa-user-o"></i> 總瀏覽人數');
                $('#all_user').html(data + '人');
            }
        }
    });
}



//-- 網頁瀏覽程度 --
function an_completion_test(an_s_date, an_e_date, com_s_date, com_e_date) {
    
    if (location.href.search(/analytics_print3/)!=-1){
        var print_css ='col-xs-4';
    }
    else{
        var print_css = '';
    }

    $.ajax({
        type: "POST",
        url: "analytics_ajax.php",
        data: {
            type: 'an_completion_test',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date
        },
        dataType: "json",
        success: function (data) {

            console.log(data);
            //console.log(data['completion']);

            //-- 比較用 --
            var com_index_total=0;
            var total_arr2=[];
            
            $('#an_completion').html('');
            $.each(data['completion'], function (index, valueOfElement) {

                let host_url='https://ws.srl.tw/';

                // 昨天日期
                var yesterday = GetDateStr(-1, 'm月d日');
                var yesterday_num = data['completion'][index]['users'].length;
                var yesterday_data = data['completion'][index]['users'][yesterday_num - 1];
                var yesterday_total = data['completion'][0]['users'][yesterday_num - 1];
                yesterday_avg_data = Math.round(yesterday_data / yesterday_total * 100);

                if (index == 0) {

                    if ($('[name="admin_per"]').val() == 'admin') {
                        var img_txt = '<a class="ch_img_a fancybox" data-fancybox-type="iframe" anchor_id="' + this['anchor_id'] + '" href="an_ch_img.php?case_id=' + this['case_id'] + '&anchor_id=' + this['anchor_id'] + '">' +
                            '<img src="' + host_url+'product_html/' + this['case_id'] + '/img/' + this['com_img'] + '" class="w-100" alt="">' +
                            '</a>';
                    }
                    else {
                        var img_txt = '<img src="' + host_url+'product_html/' + this['case_id'] + '/img/' + this['com_img'] + '" class="w-100" alt="">';
                    }
                    
                    // 昨天日期
                    var yesterday = GetDateStr(-1, 'm月d日');
                    var txt = '<div class="col-md-4 ' + print_css+'" anchor_id="' + this['anchor_id'] + '">' +
                        '<div class="an_com_div">' +
                        '<div class="img">' +
                        img_txt +
                        '</div>' +
                        '<div class="txt">' +
                        '<p>1.首頁</p>' +
                        '<div class="mb"><span class="time1_span">' + yesterday + ' <br>人數瀏覽比｜瀏覽人數：</span><span class="cut_num">100%｜' + yesterday_total+'人</span></div>' +
                        '<div class="mb com_p' + this['anchor_id'] + '"></div>' +
                        '<div class="bar"><div class="chart_div"><canvas style="height:300px; " id="lineChart' + index +'"></canvas></div></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }
                else {
                    var index_total=0;
                    var num_total=0;
                    
                    data['completion'][0]['users'].forEach(user => {
                        index_total+=user;
                    });
                    data['completion'][index]['users'].forEach(user => {
                        num_total += user;
                    });
                    var cut_num = Math.round((parseInt(num_total) / parseInt(index_total)) * 100);
                    cut_num = cut_num > 100 ? 100 : cut_num;

                    if ($('[name="admin_per"]').val() == 'admin') {
                        var img_txt = '<a class="ch_img_a fancybox" data-fancybox-type="iframe" anchor_id="' + this['anchor_id'] + '" href="an_ch_img.php?case_id=' + this['case_id'] + '&anchor_id=' + this['anchor_id'] + '">' +
                            '<img src="' + host_url+'/product_html/' + this['case_id'] + '/img/' + this['com_img'] + '" class="w-100" alt="">' +
                            '</a>';
                    }
                    else {
                        var img_txt = '<img src="' + host_url+'/product_html/' + this['case_id'] + '/img/' + this['com_img'] + '" class="w-100" alt="">';
                    }

                    
                    var txt = '<div class="col-md-4 ' + print_css+'" anchor_id="' + this['anchor_id'] + '">' +
                        '<div class="an_com_div">' +
                        '<div class="img">' +
                        img_txt +
                        '</div>' +
                        '<div class="txt">' +
                        '<p>' + (index + 1) + '.' + this['anchor_name'] + '</p>' +
                        '<div class="mb"><span class="time1_span">' + yesterday + ' <br>總人數瀏覽比｜瀏覽人數：</span><span class="cut_num">' + yesterday_avg_data + '%｜' + yesterday_data+'人</span></div>' +
                        '<div class="mb com_p' + this['anchor_id']+'"></div>' +
                        '<div class="bar"><div class="chart_div"><canvas style="height:300px; " id="lineChart' + index +'"></canvas></div></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }

                $('#an_completion').append(txt);


                //-- chart --
                var total_arr=data['completion'][0]['users'];
                var day_users_arr=[];

                var datasets1 = {
                    type:'line',
                    label: "總人數瀏覽比",
                    fill: false,
                    tension: 0,
                    pointRadius: 2,
                    borderWidth: 1,
                    backgroundColor: "#2196F3",
                    borderColor: "#2196F3",
                    datalabels: {
                        // backgroundColor:'#2196F3',
                        color: '#2196F3'
                    },
                    data: [],
                    yAxisID: 'people_percentage'
                };

                //-- 轉換人數為  當天瀏覽人數/當天到站總人數 --
                for (let j = 0; j < total_arr.length; j++) {
                    var adv_users=Math.round((this['users'][j]/total_arr[j])*100);
                    day_users_arr.push(adv_users);
                }
                datasets1.data=day_users_arr;

                //-- 轉換月日labels --
                var md_date=[];
                data['date'].forEach(day => {
                    var new_day=day.substr(5,5);
                    md_date.push(new_day);
                });

                var lineData = {
                    labels: md_date,
                    datasets: [
                        datasets1
                    ]
                };


                //-- 比較 --
                if (com_s_date != '' && com_s_date != undefined) {

                    datasets1.label ="時1-總人數瀏覽比";

                    var datasets2 = {
                        type:'line',
                        label: "時2-總人數瀏覽比",
                        fill: false,
                        tension: 0,
                        pointRadius: 2,
                        borderWidth: 1,
                        backgroundColor: "#FF6258",
                        borderColor: "#FF6258",
                        datalabels: {
                            // backgroundColor:'#FF6258',
                            color: '#FF6258'
                        },
                        data: []
                    };

                    var num_total=0;
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "analytics_ajax.php",
                        data: {
                            type: 'an_c_completion_test',
                            Tb_index: Tb_index,
                            anchor_id: this['anchor_id'],
                            com_StartDate: com_s_date,
                            com_EndDate: com_e_date
                        },
                        dataType: "json",
                        success: function (data) {
                            
                            if(index==0){
                                data.forEach(user => {
                                    com_index_total +=user;
                                    num_total+=user;
                                });

                                total_arr2=data;
                            }
                            else{
                                data.forEach(user => {
                                    num_total += user;
                                });
                            }

                            console.log(total_arr2);
                            //-- 轉換人數為  當天瀏覽人數/當天到站總人數 --
                            var day_users_arr=[];
                            for (let j = 0; j < total_arr2.length; j++) {
                                var adv_users=Math.round((data[j]/total_arr2[j])*100);
                                day_users_arr.push(adv_users);
                            }
                            datasets2.data=day_users_arr;
                            //datasets2.data = data;
                        }
                    });
                    

                    lineData.datasets.push(datasets2);

                    var cut_num2 = Math.round((parseInt(num_total) / parseInt(com_index_total)) * 100);
                    cut_num2 = cut_num2 > 100 ? 100 : cut_num2;

 
                    $('.time1_span').html('時間區間1-總人數瀏覽比：');
                    $('.com_p' + this['anchor_id']).html('<span>時間區間2-總人數瀏覽比：</span><span class="cut_num" style="color: #ff6258;">' + cut_num2 + '%</span>');
                }


                
                var datasets3 = {
                    type:'bar',
                    label: "瀏覽人數",
                    fill: false,
                    tension: 0,
                    borderWidth: 1,
                    backgroundColor: "#ccc",
                    borderColor: "#ccc",
                    data: this['users'],
                    yAxisID: 'people_num'
                };

                lineData.datasets.push(datasets3);



                var lineOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: 4.5,
                    layout: {
                        padding: {
                            top: 20,
                        }
                    },
                    legend: {
                        // align:'end',
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            pointStyle: 'circle',
                            usePointStyle: true,
                        },
                    },
                    scales: {
                        yAxes: [{
                            id:'people_percentage',
                            ticks: {
                                min:0,
                                stepSize: 2,
                                suggestedMax: 40,
                                maxTicksLimit:10,
                                callback: function (value, index, values) {
                                    return value+'%';
                                }
                            }
                        },
                        {
                            id:'people_num',
                            position:'right',
                            ticks: {
                                min:0,
                                stepSize: 2,
                                suggestedMax: 40,
                                maxTicksLimit:10,
                                callback: function (value, index, values) {
                                    return value + '人';
                                }
                            },
                            gridLines:{
                                display:false
                            }
                        }
                        ],
                        xAxes: [{
                            ticks: {
                                maxTicksLimit:10
                            }
                        }]
                    },
                    tooltips:{
                        mode:'index',
                        intersect:false,
                        backgroundColor:'#fff',
                        titleFontColor:'#000',
                        bodyFontColor:'#000',
                        borderColor:"#bbb",
                        borderWidth:1,
                        callbacks:{
                            label: function (tooltipItem, data) {
                                //-- 選擇瀏覽數百分比 --
                                if (tooltipItem.datasetIndex==0){
                                    return ' '+data.datasets[0].label+'：'+tooltipItem.yLabel+'%'
                                }
                                else{
                                    return ' ' +data.datasets[1].label + '：'+tooltipItem.yLabel+'人'
                                }
                            }
                        }
                    }
                    
                };

                var ctx = document.getElementById("lineChart" + index).getContext("2d");
                var chart = new Chart(ctx, {
                    type: 'bar', data: lineData, options: lineOptions
                });
            });

            

            $.ajax({
                type: "POST",
                url: "analytics_ajax.php",
                async: false,
                data: {
                    type: 'mem_cookie'
                },
                success: function (data) {
                    console.log(data);

                    if (data == 'admin2020040610512274') {
                        $('#an_completion .an_com_div .img').addClass('demo');
                    }
                    else {
                        $('#an_completion .an_com_div .img').removeClass('demo');
                    }
                }
            });

        }
    });

}






//-- 網頁瀏覽程度 --
function an_completion(an_s_date, an_e_date, com_s_date, com_e_date) {

    if (location.href.search(/analytics_print3/) != -1) {
        var print_css = 'col-xs-4';
    }
    else {
        var print_css = '';
    }

    $.ajax({
        type: "POST",
        url: "analytics_ajax.php",
        data: {
            type: 'an_completion',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date
        },
        dataType: "json",
        success: function (data) {


            $('#an_completion').html('');
            $.each(data, function (index, valueOfElement) {
                if (index == 0) {

                    if ($('[name="admin_per"]').val() == 'admin') {
                        var img_txt = '<a class="ch_img_a fancybox" data-fancybox-type="iframe" anchor_id="' + this['anchor_id'] + '" href="an_ch_img.php?case_id=' + this['case_id'] + '&anchor_id=' + this['anchor_id'] + '">' +
                            '<img src="/product_html/' + this['case_id'] + '/img/' + this['com_img'] + '" class="w-100" alt="">' +
                            '</a>';
                    }
                    else {
                        var img_txt = '<img src="/product_html/' + this['case_id'] + '/img/' + this['com_img'] + '" class="w-100" alt="">';
                    }


                    var txt = '<div class="col-md-3 ' + print_css + '" anchor_id="' + this['anchor_id'] + '">' +
                        '<div class="an_com_div">' +
                        '<div class="img">' +
                        img_txt +
                        '</div>' +
                        '<div class="txt">' +
                        '<p>1.首頁</p>' +
                        '<span>總人數瀏覽比：</span><span class="cut_num">100%</span>' +
                        '<div class="bar"><b style="width:100%;"></b></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }
                else {
                    var cut_num = Math.round((parseInt(data[index]['users']) / parseInt(data[0]['users'])) * 100);
                    cut_num = cut_num > 100 ? 100 : cut_num;

                    if ($('[name="admin_per"]').val() == 'admin') {
                        var img_txt = '<a class="ch_img_a fancybox" data-fancybox-type="iframe" anchor_id="' + this['anchor_id'] + '" href="an_ch_img.php?case_id=' + this['case_id'] + '&anchor_id=' + this['anchor_id'] + '">' +
                            '<img src="/product_html/' + this['case_id'] + '/img/' + this['com_img'] + '" class="w-100" alt="">' +
                            '</a>';
                    }
                    else {
                        var img_txt = '<img src="/product_html/' + this['case_id'] + '/img/' + this['com_img'] + '" class="w-100" alt="">';
                    }

                    var txt = '<div class="col-md-3 ' + print_css + '" anchor_id="' + this['anchor_id'] + '">' +
                        '<div class="an_com_div">' +
                        '<div class="img">' +
                        img_txt +
                        '</div>' +
                        '<div class="txt">' +
                        '<p>' + (index + 1) + '.' + this['anchor_name'] + '</p>' +
                        '<span>總人數瀏覽比：</span><span class="cut_num">' + cut_num + '%</span>' +
                        '<div class="bar"><b style="width:' + cut_num + '%;"></b></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }

                $('#an_completion').append(txt);
            });

            $.ajax({
                type: "POST",
                url: "analytics_ajax.php",
                async: false,
                data: {
                    type: 'mem_cookie'
                },
                success: function (data) {
                    console.log(data);

                    if (data == 'admin2020040610512274') {
                        $('#an_completion .an_com_div .img').addClass('demo');
                    }
                    else {
                        $('#an_completion .an_com_div .img').removeClass('demo');
                    }
                }
            });


            //-- 比較 --
            if (com_s_date != '' && com_s_date != undefined) {
                $.ajax({
                    type: "POST",
                    url: "analytics_ajax.php",
                    data: {
                        type: 'an_c_completion',
                        Tb_index: Tb_index,
                        com_StartDate: com_s_date,
                        com_EndDate: com_e_date
                    },
                    dataType: "json",
                    success: function (data) {
                        //console.log(data);
                        //$('#an_completion .case_img_ul').html('');
                        $.each(data, function (index, valueOfElement) {
                            if (index == 0) {

                                var txt_span = '<span>總人數瀏覽比： </span><span class="cut_num2">100%</span>' +
                                    '<div class="bar"><b class="b_c" style="width:100%;"></b></div>';
                                $('#an_completion [anchor_id="' + this['anchor_id'] + '"] .txt').append(txt_span);
                            }
                            else {
                                var cut_num = Math.round((parseInt(data[index]['users']) / parseInt(data[0]['users'])) * 100);
                                cut_num = cut_num > 100 ? 100 : cut_num;

                                var txt_span = '<span>總人數瀏覽比：</span><span class="cut_num2">' + cut_num + '%</span>' +
                                    '<div class="bar"><b class="b_c" style="width:' + cut_num + '%;"></b></div>';
                                $('#an_completion [anchor_id="' + this['anchor_id'] + '"] .txt').append(txt_span);
                            }

                            //  var cut_num_ad=(cut_num/parseInt(data[0]['users']))*100;
                            //  $('.case_img_ul li:nth-child('+(index+1)+') b.b_c').css('width', cut_num_ad+'%');
                        });
                    }
                });
            }
        }
    });

}





//----------- 來信 -----------------
function an_mail(an_s_date, an_e_date) {


    an_num_mail(an_s_date, an_e_date, '', '#ch_status [status=""] b');
    an_num_mail(an_s_date, an_e_date, '0', '#ch_status [status="0"] b');
    an_num_mail(an_s_date, an_e_date, '1', '#ch_status [status="1"] b');
    an_num_mail(an_s_date, an_e_date, '2', '#ch_status [status="2"] b');
    
    $.ajax({
        type: "POST",
        url: "analytics_ajax.php",
        data: {
            type: 'an_mail',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date
        },
        dataType: "json",
        success: function (data) {
            $('.slide_mail_btn').html('展開');
            $('#an_mail_div').css('display', 'none');
            $('#an_mail_div tbody').html('');
            var data_count=data.length;

            $.each(data, function (index, valueOfElement) {

                if (Tb_index == 'case2018101812190565' || Tb_index == 'case2018071915501554' || Tb_index=='case2020042814521344'){
                    var use_name = this['use_name'].substr(0, 1) + 'XX';
                    var phone = this['phone'].substr(0, 7) + 'XX';
                    var use_mail = this['use_mail'].split('@');
                    var use_mail_name = use_mail[0].substr(0, 3) + 'XXXXX';
                }
                else{
                    var use_name = this['use_name'];
                    var phone = this['phone'];
                    var use_mail = this['use_mail'].split('@');
                    var use_mail_name = use_mail[0];
                }
                

                if(this['is_process']=='0'){
                    var is_process ='<span class="label label-danger">未處理</span>';
                }
                else if (this['is_process'] == '1'){
                    var is_process = '<span class="label label-primary">已處理</span>';
                }
                else{
                    var is_process = '<span class="label label-warning">處理中</span>';
                }
                
                
                var txt = '<tr>' +
                    '<td data-th="#">' + (data_count - index) +'<span style="padding:5px 10px;" class="ph_show">'+is_process+ '</span></td>' +
                    '<td data-th="時間">' + this['set_time'] + '</td>' +
                    '<td data-th="姓名">' + use_name + '</td>' +
                    '<td data-th="電話">' + phone + '</td>' +
                    '<td class="print_none" data-th="E-mail">' + use_mail_name + '@' + use_mail[1] + '</td>' +
                    '<td data-th="來源" >' + this['utm_source'] + '</td>' +
                    '<td class="print_none" data-th="狀態" class="ph_close">' + is_process + '</td>' +
                    '<td class="print_none" data-th="管理">'+
                    '<select name="is_process" id="is_process" case_id="' + this['Tb_index']+'">'+
                           '<option value="">-- 請選擇 --</option>' +
                           '<option value="0">未處理</option>'+
                           '<option value="2">處理中</option>'+
                           '<option value="1">已處理</option>'+
                       '</select>'+
                    '</td>'+
                    '</tr>';

                $('#an_mail_div tbody').append(txt);

            });
        }
    });
}



//----------- 來信 (選擇狀態)-----------------
function an_status_mail(an_s_date, an_e_date, status) {
    $.ajax({
        type: "POST",
        url: "analytics_ajax.php",
        data: {
            type: 'an_status_mail',
            Tb_index: Tb_index,
            an_StartDate: an_s_date,
            an_EndDate: an_e_date,
            is_process: status
        },
        dataType: "json",
        success: function (data) {
            // $('.slide_mail_btn').html('展開');
            // $('#an_mail_div').css('display', 'none');
             $('#an_mail_div tbody').html('');
            var data_count = data.length;

            $.each(data, function (index, valueOfElement) {
                var use_name = this['use_name'].substr(0, 1) + 'XX';
                var phone = this['phone'].substr(0, 7) + 'XX';
                var use_mail = this['use_mail'].split('@');
                var use_mail_name = use_mail[0].substr(0, 3) + 'XXXXX';

                if (this['is_process'] == '0') {
                    var is_process = '<span class="label label-danger">未處理</span>';
                }
                else if (this['is_process'] == '1') {
                    var is_process = '<span class="label label-primary">已處理</span>';
                }
                else {
                    var is_process = '<span class="label label-warning">處理中</span>';
                }


                var txt = '<tr>' +
                    '<td data-th="#">' + (data_count - index) + '<span style="padding:5px 10px;" class="ph_show">' + is_process + '</span></td>' +
                    '<td data-th="時間">' + this['set_time'] + '</td>' +
                    '<td data-th="姓名">' + use_name + '</td>' +
                    '<td data-th="電話">' + phone + '</td>' +
                    '<td data-th="E-mail">' + use_mail_name + '@' + use_mail[1] + '</td>' +
                    '<td data-th="狀態" class="ph_close">' + is_process + '</td>' +
                    '<td data-th="管理">' +
                    '<select name="is_process" id="is_process" case_id="' + this['Tb_index'] + '">' +
                    '<option value="">-- 請選擇 --</option>' +
                    '<option value="0">未處理</option>' +
                    '<option value="2">處理中</option>' +
                    '<option value="1">已處理</option>' +
                    '</select>' +
                    '</td>' +
                    '</tr>';

                $('#an_mail_div tbody').append(txt);
            });
        }
    });
}



function an_num_mail(an_s_date, an_e_date, status, dom) {
    $.ajax({
        type: "POST",
        url: "analytics_ajax.php",
        data: {
          type: 'an_num_mail',
            Tb_index: Tb_index,
          an_StartDate: an_s_date,
          an_EndDate: an_e_date,
          is_process: status
        },
        success: function (data) {
           
            $(dom).html(data);
            //console.log(data);
        }
    });
}


//-- 流量來源辨識 --
function src_ch(data_name, data_num) {
    
    //-- 不顯示 --
    var show_0_arr = [
        'm.facebook.com', 
        'l.facebook.com', 
        'tw.search.yahoo.com', 
        'tpc.googlesyndication.com', 
        'googleads.g.doubleclick.net', 
        'bknet.com.tw', 
        'picsee.io',
        'leisure.freeadwifi.com.tw',
        'google.com'
    ];

    if (data_name.search(/none/) > -1) {
        var find_name = '直接連結';
        show = 1;
    }
    else if (data_name.search(/organic/) > -1) {
        var new_name = data_name.split('/');
        var find_name = new_name[0] + '搜尋';
        show = 1;
    }
    else if (data_name.search(/referral/) > -1) {

        var new_name = data_name.split('/');

        if (data_name.search(/m.facebook.com/) > -1) {
            var find_name = '手機板FB推薦連結';
            show = 0;
        }
        else if (data_name.search(/facebook.com/) > -1) {
            var find_name = '電腦版FB推薦連結';
            show = 1;
        }
        else if (data_name.search(/market.ltn.com.tw/) > -1) {
            var find_name = '自由時報推薦連結';
            show = 1;
        }
        else if (data_name.search(/xy168.com.tw/) > -1) {
            var find_name = '官網推薦連結';
            show = 1;
        }
        else if (data_name.search(/gotv.ctitv.com.tw/) > -1) {
            var find_name = '中天新聞推薦連結';
            show = 1;
        }
        else {
            var find_name = new_name[0] + '推薦連結';
            show = -1;
        }
        
        //-- 不顯示 --
        for (let i = 0; i < show_0_arr.length; i++) {
            if (data_name.search(show_0_arr[i]) > -1) {
                show = 0;
            }
        }

    }
    else if (data_name.search(/(not set)/) > -1) {
        var find_name = '無';
        show = 0;
    }
    else if (data_name.search(/Campaigns/) > -1) {
        var new_name = data_name.split('/');
        var find_name = new_name[0] + 'google廣告';
        show = 1;
    }
    else if (data_name.search(/蝬脣/) > -1) {
        var find_name = '無';
        show = 0;
    }
    else {
        var find_name = data_name;
        show = 1;
    }

    if (show == 1) {
        return [find_name, data_num];
    }
    else {
        return [undefined, undefined];
    }
}


/*-- 千分位 -- */
function toCurrency(num) {
    var parts = num.toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return parts.join('.');
}



//-- 全分析表 AJAX --
function all_an_ajax(an_StartDate, an_EndDate, com_StartDate, com_EndDate) {

    //-- 刪除圖例 --
    $('.c3_legend').html('');
    
     //-- 獲取總人數 --
    ajax_all_user(an_StartDate, an_EndDate, com_StartDate, com_EndDate);

    setTimeout(() => {
       
        ajax_sex(data_sex, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        // ajax_userType(data_userType, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        // ajax_userType_per(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        // ajax_userCount(data_userCount, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_old(data_old, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_media(data_media, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_tool_btn(data_tool_btn, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_src_num(data_src_num, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        //ajax_src_num_d(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_city(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_timeOnSite(data_timeOnSite, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_data_use(data_use, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_max_week_user(data_use, an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        ajax_min_month_user(data_use, an_StartDate, an_EndDate, com_StartDate, com_EndDate);

        //an_completion(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        an_completion_test(an_StartDate, an_EndDate, com_StartDate, com_EndDate);
        an_mail(an_StartDate, an_EndDate);

        if (an_StartDate == '' && an_EndDate == '') {
            ajax_month_src_num(data_month_src_num);
        }
    }, 300);
    
}



//-- 圓餅圖 圖例修改 --
function ch_an_legend (an, dom_id) {
    var an_data = an.data();
    var data_name = [];
    for (let i = 0; i < an_data.length; i++) {
        data_name.push(an_data[i]['id']);
    }

    d3.select(dom_id).insert('div', '.chart').attr('class', 'legend').selectAll('div')
        .data(data_name)
        .enter().append('div')
        .attr('data-id', function (id) {
            return id;
        })
        .html(function (id) {
            return '<i>'+id+'</i>';
        })
        .each(function (id) {
            d3.select(this).append('span').style('background-color', an.color(id));
            var data = an.data.shown(id);
            var num = data[0]['values'][0]['value'];
            $(dom_id+' [data-id="' + id + '"]').append('<b>' + num + '人</b>');
            // console.log(an.data());
        })
        .on('mouseover', function (id) {
            an.focus(id);
        })
        .on('mouseout', function (id) {
            an.revert();
        });
}


//-- 圓餅圖 改顏色 --
function ch_an_color(an, data_name) {
    var name_color = {};
    var color = ['#ff6258', '#2196f3', '#ffb22b', '#26c6da', '#7460ee'];
    var data_name_num = data_name.length > 5 ? 5 : data_name.length;
    for (let i = 0; i < data_name_num; i++) {
        var new_data_name = src_ch(data_name[i],0);
        name_color[new_data_name[0]] = d3.rgb(color[i]);

    }
    an.data.colors(name_color);
}



//-- 時間軸 圖例修改 --
function ch_an_time_legend(an, dom_id) {
    var an_data = an.data();
    var data_name = [];
    for (let i = 0; i < an_data.length; i++) {
        data_name.push(an_data[i]['id']);
    }

    d3.select(dom_id).insert('div', '.chart').attr('class', 'legend').selectAll('div')
        .data(data_name)
        .enter().append('div')
        .attr('data-id', function (id) {
            return id;
        })
        .html(function (id) {
            return '<i>' + id + '</i>';
        })
        .each(function (id) {
            d3.select(this).append('span').style('background-color', an.color(id));
        })
        .on('mouseover', function (id) {
            an.focus(id);
        })
        .on('mouseout', function (id) {
            an.revert();
        });
}

