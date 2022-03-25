/**
 * 一頁式分析圖表 CLASS
 * 
 * @extends Chartjs_class 
 */
class CaseAn extends Chartjs_class{
    

    options={
        case_id:'',
        s_date: '',
        e_date: '',
    }

    _is_chart={
        user_chart:true,
        sex_chart:true,
        years_chart: true,
        city_chart: true,
        media_chart: true,
        event_chart: true,
        month_src_chart: true,
        src_chart: true,
        completion_chart: true,
        mail_chart: true
    };

    //-- 私密變數 前綴加# --
    #pie_color = ['#FF6258', '#2196F3', '#FFB22B', '#7460EE', '#FF7F0E', '#2CA02C', '#1b671b', '#868686', '#d34b42', '#1c77bf', '#c58a24', '#5e5789', '#d634d9', '#27af9c'];
    #an_option={
        destroy:false
    };


    #_user_ch;


    /**
     * 每日使用人數
     * 
     * @param {String} chart_id 圖表元素ID 'user_chart'
     * @param {Array} user_data 人數資料陣列
     * @param {Array} date 日期陣列
     * @param {String} max_user_date 最大人數日期
     * @param {String} min_user_date 最小人數日期
     * @returns 圖表物件
     */
    user_chart({chart_id='user_chart', user_data, date, max_user_date, min_user_date}){
        let user_line_options = this.line_options();
        user_line_options.annotation = {
            drawTime: 'afterDatasetsDraw',
            annotations: [
                {
                    id: 'vline1',
                    type: 'line',
                    mode: 'vertical',
                    scaleID: 'x-axis-0',
                    value: max_user_date,
                    borderColor: '#1c84c6',
                    borderWidth: 1,
                    borderDash: [10, 5],
                    label: {
                        backgroundColor: "#1c84c6",
                        position: 'bottom',
                        yAdjust: 10,
                        content: "最大瀏覽人數",
                        enabled: true
                    }
                },
                {
                    id: 'vline2',
                    type: 'line',
                    mode: 'vertical',
                    scaleID: 'x-axis-0',
                    value: min_user_date,
                    borderColor: '#ff6258',
                    borderWidth: 1,
                    borderDash: [10, 5],
                    label: {
                        backgroundColor: "#ff6258",
                        position: 'top',
                        yAdjust: 10,
                        content: "最小瀏覽人數",
                        enabled: true
                    }
                }
            ]
        };
        let user__datasets = this.line_datasets("使用人數", "#1C84C6", user_data);
        let _user_ch = this.line_chart(chart_id, date, [user__datasets], user_line_options);
        return _user_ch;
    }



    /**
     * 每日來信來電數(進階版)
     * 
     * @param {String} chart_id 圖表元素ID 'mail_date_chart'
     * @param {Array} mail_data 來信數資料陣列
     * @param {Array} phone_data 來電數資料陣列
     * @param {Array} date 日期陣列
     * @param {String} max_mail_date 最大來信數日期
     * @param {String} max_phone_date 最大來電數日期
     * @returns 圖表物件
     */
    mail_date_chart({ chart_id = 'mail_date_chart', mail_data, phone_data, date, max_mail_date, max_phone_date }) {
        let user_line_options = this.line_options();
        user_line_options.annotation = {
            drawTime: 'afterDatasetsDraw',
            annotations: [
                {
                    id: 'vline1',
                    type: 'line',
                    mode: 'vertical',
                    scaleID: 'x-axis-0',
                    value: max_mail_date,
                    borderColor: '#dd7d06',
                    borderWidth: 1,
                    borderDash: [10, 5],
                    label: {
                        backgroundColor: "#dd7d06",
                        position: 'bottom',
                        yAdjust: 10,
                        content: "最大來信數",
                        enabled: true
                    }
                },
                {
                    id: 'vline2',
                    type: 'line',
                    mode: 'vertical',
                    scaleID: 'x-axis-0',
                    value: max_phone_date,
                    borderColor: '#06c2d5',
                    borderWidth: 1,
                    borderDash: [10, 5],
                    label: {
                        backgroundColor: "#06c2d5",
                        position: 'bottom',
                        yAdjust: 10,
                        content: "最大來電數",
                        enabled: true
                    }
                }
            ]
        };
        let mail__datasets = this.line_datasets("來信數", "#dd7d06", mail_data);
        let phone__datasets = this.line_datasets("來電數", "#06c2d5", phone_data);
        let _mail_date_ch = this.line_chart(chart_id, date, [phone__datasets, mail__datasets], user_line_options);
        return _mail_date_ch;
    }



    /**
     * 每日互動率 (跳出率反轉)
     * 
     * @param {String} chart_id 圖表元素ID 'user_chart'
     * @param {Array} user_data 人數資料陣列
     * @param {Array} date 日期陣列
     * @param {String} max_br_date 最大互動率日期
     * @param {String} min_br_date 最小互動率日期
     * @returns 圖表物件
     */
    BounceRate_chart({ chart_id = 'BounceRate_chart', user_data, date, max_br_date, min_br_date }) {
        let BounceRate_line_options = this.line_options();

        BounceRate_line_options.scales.yAxes = [{
                ticks: {
                    min: 0,
                    stepSize: 2,
                    suggestedMax: 40,
                    maxTicksLimit: 10,
                    callback: function (value, index, values) {
                        return value + '%';
                    }
                }
        }];

        BounceRate_line_options.tooltips.callbacks = {
            label: function (tooltipItem, data) {
                //-- 選擇瀏覽數百分比 --
                if (tooltipItem.datasetIndex == 0) {
                    return ' ' + data.datasets[0].label + '：' + tooltipItem.yLabel + '%'
                }
            }
        };

        BounceRate_line_options.annotation = {
            drawTime: 'afterDatasetsDraw',
            annotations: [
                {
                    id: 'vline1',
                    type: 'line',
                    mode: 'vertical',
                    scaleID: 'x-axis-0',
                    value: max_br_date,
                    borderColor: '#1c84c6',
                    borderWidth: 1,
                    borderDash: [10, 5],
                    label: {
                        backgroundColor: "#1c84c6",
                        position: 'bottom',
                        yAdjust: 10,
                        content: "最大互動率",
                        enabled: true
                    }
                }

            ]
        };
        let BounceRate__datasets = this.line_datasets("互動比率", "#1C84C6", user_data);
        let _BounceRate_ch = this.line_chart(chart_id, date, [BounceRate__datasets], BounceRate_line_options);
        return _BounceRate_ch;
    }



    /**
     * 使用者性別
     * 
     * @param {String} chart_id 圖表元素ID 'sex_chart'
     * @param {Array} user_data 人數資料陣列
     * @param {Number} total_user 總人數
     * @param {String} legend_id 圓餅條列顯示元素ID 'sex_legend'
     * @returns 圖表物件
     */
    sex_chart({chart_id='sex_chart', user_data, total_user=0, legend_id='sex_legend'}){
            let sex_data = [];
            //let all_user = total_user;
            user_data.forEach(sex => {
                sex_data.push(sex.total);
                //all_user -= sex.total;
            });
            //sex_data[2] = all_user;
            let sex__datasets = this.pie_datasets("使用人數", ['#FF6258', '#2196F3'], sex_data);
            let sex__labels = ['女性', '男性'];
            let _sex__chart = this.pie_chart(chart_id, sex__labels, [sex__datasets], 'pie');
            $(`#${legend_id}`).html(_sex__chart.generateLegend());
            return _sex__chart;
    }

    /**
     * 使用者年齡
     * 
     * @param {String} chart_id 圖表元素ID 'years_chart'
     * @param {Array} user_data 人數資料陣列
     * @returns 圖表物件
     */
    years_chart({chart_id='years_chart', user_data}){
            let years_data = [];
            let years__labels = [];
            user_data.forEach(years => {
                years__labels.push(years.years_type + '歲');
                years_data.push(years.total);
            });
            let years__datasets = this.bar_datasets("使用人數", "#2196F3", years_data);
            let _years__chart = this.bar_chart(chart_id, years__labels, [years__datasets]);
            return _years__chart;
    }
    
    /**
     * 使用者地區
     * 
     * @param {String} chart_id 圖表元素ID 'city_chart'
     * @param {Array} user_data 人數資料陣列
     * @returns 圖表物件
     */
    city_chart({chart_id='city_chart', user_data}){
        let city_data = [];
        let city__labels = [];
        user_data.forEach(city => {
            city__labels.push(city.tw_name);
            city_data.push(city.total);
        });
        let city__datasets = this.bar_datasets("使用人數", "#1da8af", city_data);
        let _city__chart = this.bar_chart(chart_id, city__labels, [city__datasets]);
        return _city__chart;
    }

    /**
     * 使用媒體
     * 
     * @param {String} chart_id 圖表元素ID 'media_chart'
     * @param {Array} user_data 人數資料陣列
     * @returns 圖表物件
     */
    media_chart({chart_id='media_chart', user_data}){
        let media_data = [];
        let media__labels = ['桌機', '手機', '平板'];
        user_data.forEach(media => {
            media_data.push(media.total);
        });
        let media__datasets = this.bar_datasets("使用人數", "#1C84C6", media_data);
        let _media__chart = this.bar_chart(chart_id, media__labels, [media__datasets]);
        return _media__chart;
    }

    /**
     * 使用的功能
     * 
     * @param {String} chart_id 圖表元素ID 'event_chart'
     * @param {Array} user_data 人數資料陣列
     * @param {String} legend_id 圓餅條列顯示元素ID 'event_legend'
     * @returns 圖表物件
     */
    event_chart({chart_id='event_chart', user_data, legend_id='event_legend'}){
        let event_data = [];
        let event__labels = [];
        user_data.forEach(event => {
            event_data.push(event.total);
            event__labels.push(event.event_type);
        });
        let event__datasets = this.pie_datasets("使用人數", this.#pie_color, event_data);
        let _event__chart = this.pie_chart(chart_id, event__labels, [event__datasets], 'doughnut');
        $(`#${legend_id}`).html(_event__chart.generateLegend());
        return _event__chart;
    }
    

    /**
     * 當月流量來源
     * 
     * @param {String} chart_id 圖表元素ID 'month_src_chart'
     * @param {Array} user_data 人數資料陣列
     * @param {String} legend_id 圓餅條列顯示元素ID 'month_src_legend'
     * @returns 圖表物件
     */
    month_src_chart({chart_id='month_src_chart', user_data, legend_id='month_src_legend'}){
        let month_src_data = [];
        let month_src__labels = [];
        user_data.forEach(month_src => {
            let ch_src = src_ch(month_src.src_type, month_src.total);
            if (ch_src[0] != undefined && ch_src[1] != 0) {
                month_src_data.push(ch_src[1]);
                month_src__labels.push(ch_src[0]);
            }

        });
        let month_src__datasets = this.pie_datasets("使用人數", this.#pie_color, month_src_data);
        let _month_src__chart = this.pie_chart(chart_id, month_src__labels, [month_src__datasets], 'pie');
        $(`#${legend_id}`).html(_month_src__chart.generateLegend());
        return _month_src__chart;
    }
    

    /**
     * 流量來源&&當月流量來源
     * 
     * @param {String} chart_id 圖表元素ID 'src_chart'
     * @param {Array} user_data 人數資料陣列
     * @param {String} legend_id 圓餅條列顯示元素ID 'src_legend'
     * @returns 圖表物件
     */
    src_chart({chart_id='src_chart', user_data, legend_id='src_legend'}){
        let src_data = [];
        let src__labels = [];
        user_data.forEach(src => {
            let ch_src = this.src_ch(src.src_type, src.total);
            if (ch_src[0] != undefined && ch_src[1] != 0) {
                src_data.push(ch_src[1]);
                src__labels.push(ch_src[0]);
            }
        });
        let src__datasets = this.pie_datasets("使用人數", this.#pie_color, src_data);
        let _src__chart = this.pie_chart(chart_id, src__labels, [src__datasets], 'pie');
        $(`#${legend_id}`).html(_src__chart.generateLegend());
        return _src__chart;
    }



    /**
     * 來源使用時間
     * 
     * @param {String} chart_id 圖表元素ID 'src_time_chart'
     * @param {Array} user_data 人數資料陣列
     * @param {Array} src_data  流量資料陣列
     * @returns 圖表物件
     */
    src_time_chart({ chart_id = 'src_time_chart', user_data, src_data }) {
        let src_time_tr='';
        let max_src=0;
        src_data.forEach(src => {
            let ch_src = this.src_ch(src.src_type, src.total);
            if (ch_src[0] != undefined && ch_src[1] != 0) {
                max_src += parseInt(src.total);
            }
        });
        let data_num = src_data.length > 10 ? 10 : src_data.length;
        let avg_max_src = Math.round(max_src / data_num);
        src_data.forEach(src => {
            let ch_src = this.src_ch(src.src_type, src.total);
            if (ch_src[0] != undefined && ch_src[1] != 0) {
                let avg_time=user_data.find(function (item) {
                    return item.src_type== src.src_type;
                });
                console.log(avg_time);
                let high_line = src.total >= avg_max_src ? '':'d-none';
                src_time_tr += `<div class="${high_line} st_ch_box">
                                    <h3>${ch_src[0]}</h3>
                                    <div class="st_dt_box">
                                    <p class="s_sec">平均使用時間(秒)：${Math.round(parseInt(avg_time.avg_time))}秒</p>
                                    <p class="s_num">來源數：${ch_src[1]}人</p>
                                    </div>
                                </div>`;
            }
        });

        $(`#${chart_id}`).html(src_time_tr);
    }
    

    /**
     * 網頁瀏覽程度
     * 
     * @param {String} DIV_id DIV元素ID 'an_completion'
     * @param {String} chart_id 圖表元素ID 'mixChart'
     * @param {String} div_class DIV元素Class 'col-md-4'
     * @param {Array} completion_data 人數資料陣列
     * @param {String} date 日期陣列
     * @returns 圖表物件
     */
    completion_chart({DIV_id='an_completion', chart_id='mixChart', div_class='col-md-4', completion_data, date}){
        
        $(`#${DIV_id}`).html('');

        let yesterday = GetDateStr(-1, 'm月d日');
        let yesterday_num = completion_data[0]['users'].length;
        let yesterday_total = completion_data[0]['users'][yesterday_num - 1];
        let _this=this;

        $.each(completion_data, function (index, valueOfElement) {

            // 昨天日期
            let yesterday_data = this['users'][yesterday_num - 1];
            let yesterday_avg_data = Math.round(yesterday_data / yesterday_total * 100);
            let index_num = index + 1;

            let _each_this=this;
            $.get("/js/an_Class/view/completion_html.html",function (data) {

                var html=data;
                html=html.replaceAll("{{div_class}}", div_class);
                html=html.replaceAll("{{case_id}}", _each_this['case_id']);
                html=html.replaceAll("{{com_img}}", _each_this['com_img']);
                html=html.replaceAll("{{index_num}}", index_num);
                html=html.replaceAll("{{anchor_name}}", _each_this['anchor_name']);
                html=html.replaceAll("{{yesterday}}", yesterday);
                html=html.replaceAll("{{yesterday_avg_data}}", yesterday_avg_data);
                html=html.replaceAll("{{yesterday_data}}", yesterday_data);
                html=html.replaceAll("{{chart_id}}", chart_id);
                html=html.replaceAll("{{index_num}}", index_num);

                $(`#${DIV_id}`).append(html);


                //-- 轉換月日labels --
                var md_date = [];
                date.forEach(day => {
                    var new_day = day.substr(5, 5);
                    md_date.push(new_day);
                });

                //-- 轉換人數為  當天瀏覽人數/當天到站總人數 --
                let total_arr = completion_data[0]['users'];
                let day_users_arr = [];
                for (let j = 0; j < total_arr.length; j++) {
                    var adv_users = Math.round((_each_this['users'][j] / total_arr[j]) * 100);
                    day_users_arr.push(adv_users);
                }

                let completion_line_datasets = _this.line_datasets("總人數瀏覽比", "#2196F3", day_users_arr);
                let completion_bar_datasets = _this.bar_datasets("瀏覽人數", "#ccc", _each_this['users']);
                completion_line_datasets.yAxisID = 'people_percentage';
                completion_bar_datasets.yAxisID = 'people_num';
                completion_line_datasets.type = 'line';
                completion_bar_datasets.type = 'bar';

                completion_line_datasets.pointRadius = 2;
                completion_line_datasets.borderWidth = 1;

                let completion_opt = _this.bar_options();
                completion_opt.plugins.datalabels = false;
                completion_opt.scales.yAxes = [{
                    id: 'people_percentage',
                    ticks: {
                        min: 0,
                        stepSize: 2,
                        suggestedMax: 40,
                        maxTicksLimit: 10,
                        callback: function (value, index, values) {
                            return value + '%';
                        }
                    }
                },
                {
                    id: 'people_num',
                    position: 'right',
                    ticks: {
                        min: 0,
                        stepSize: 2,
                        suggestedMax: 40,
                        maxTicksLimit: 10,
                        callback: function (value, index, values) {
                            return value + '人';
                        }
                    },
                    gridLines: {
                        display: false
                    }
                }];

                completion_opt.tooltips.callbacks = {
                    label: function (tooltipItem, data) {
                        //-- 選擇瀏覽數百分比 --
                        if (tooltipItem.datasetIndex == 0) {
                            return ' ' + data.datasets[0].label + '：' + tooltipItem.yLabel + '%'
                        }
                        else {
                            return ' ' + data.datasets[1].label + '：' + tooltipItem.yLabel + '人'
                        }
                    }
                };
                let completion_ch = _this.bar_chart(`${chart_id}${index_num}`, md_date, [completion_line_datasets, completion_bar_datasets], 'bar', completion_opt);
            });

        });
    
    }
    

    /**
     * 預約賞屋來信
     * 
     * @param {String} DIV_id DIV元素ID '#an_mail_div table tbody'
     * @param {Boolean} show_is_process 判斷是否顯示處理狀態功能
     * @param {Array} user_data 賞屋來信資料陣列
     */
    mail_list({DIV_id='#an_mail_div table tbody', show_is_process=true, user_data}){
        $(DIV_id).html('');
        $.each(user_data, function (index, valueOfElement) {
            let index_num = index + 1;
            let is_process = '';
            switch (this.is_process) {
                case '0':
                    is_process = `<span class="label label-danger">未處理</span>`;
                    break;
                case '1':
                    is_process = `<span class="label label-primary">已處理</span>`;
                    break;
                case '2':
                    is_process = `<span class="label label-warning">處理中</span>`;
                    break;
            }

            let _each_this=this;
            $.get("/js/an_Class/view/mail_html.html?2",function (data){
               
                var html=data;
                let is_process_style=show_is_process ? '':'display:none;';
                html=html.replaceAll("{{index_num}}", index_num);
                html=html.replaceAll("{{set_time}}", _each_this['set_time']);
                html=html.replaceAll("{{use_name}}", _each_this['use_name']);
                html=html.replaceAll("{{phone}}", _each_this['phone']);
                html=html.replaceAll("{{use_mail}}", _each_this['use_mail']);
                html=html.replaceAll("{{utm_source}}", _each_this['utm_source']);
                html=html.replaceAll("{{show_is_process}}", is_process_style);
                html=html.replaceAll("{{is_process}}", is_process);
                html=html.replaceAll("{{Tb_index}}", _each_this['Tb_index']);

                $(DIV_id).append(html);
            });
            // let mail_html = `
            //         <tr>
            //                 <td>${index_num}</td>
            //                 <td>${this.set_time}</td>
            //                 <td>${this.use_name}</td>
            //                 <td>${this.phone}</td>
            //                 <td>${this.use_mail}</td>
            //                 <td>${this.utm_source}</td>
            //                 <td>${is_process}</td>
            //                 <td>
            //                 <select name="is_process" id="is_process" case_id="${this.Tb_index}">
            //                     <option value="">-- 請選擇 --</option>
            //                     <option value="0">未處理</option>
            //                     <option value="2">處理中</option>
            //                     <option value="1">已處理</option>
            //                 </select>
            //                 </td>
            //             </tr>`;
            // $(DIV_id).append(mail_html);
        });
    }


    /**
     * 新舊訪客/回訪率
     * 
     * @param {String} chart_id DIV元素ID 'visit_chart'
     * @param {Array} user_data 人數資料陣列
     * @param {String} legend_id 圓餅條列顯示元素ID 'visit_legend'
     * @returns 圖表物件
     */
    visit_chart({chart_id='visit_chart', user_data, legend_id='visit_legend'}){
        let data = [];
        let total=0;
        user_data.forEach(one => {
            data.push( parseInt(one.total));
            total+=parseInt( one.total);
        });
        let _datasets = this.pie_datasets("使用人數", ['#bdbdbd', '#20b722'], data);
        let _labels = ['新訪客', '回訪客'];
        let __chart = this.pie_chart(chart_id, _labels, [_datasets], 'pie');
        $(`#${legend_id}`).html(__chart.generateLegend());

        return __chart;
    }


    /**
     * 流量來源辨識
     * 
     * @param {String} data_name 來源名稱
     * @param {Number} data_num 來源數量
     * @returns 
     */
    src_ch(data_name, data_num) {
        let show=1;
    
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

}