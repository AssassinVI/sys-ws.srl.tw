
/**
 * Chartjs 分析常用功能
 * 
 * 折線圖、長條圖、圓餅圖
 */
 class Chartjs_class{
    //-- 建構器 --
    constructor() {
        //-- 設定 圖表上數值設定 --
        Chart.helpers.merge(Chart.defaults.global.plugins.datalabels, {
            borderRadius: 5,
            align: 'end',
            offset: 2,
            font: {
                size: 15
            }
        });

        Chart.defaults.global.defaultFontSize = 15;
    }

    /**
     * 折線圖option物件
     * 
     * @returns {object} 設定物件
     */
    line_options () {
        var option = {
            responsive: true,
            maintainAspectRatio: false,
            devicePixelRatio: 2,
            aspectRatio: 4.5,
            layout: {
                padding: {
                    top: 40,
                    right: 20
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
                    ticks: {
                        //stepSize: 2,
                        suggestedMax: 10
                    },
    
                }]
            },
            plugins: {
                datalabels: {
                    backgroundColor:'#e9ecef',
                    borderRadius:5,
                    align:'end',
                    offset: 4,
                    font:{
                        size:13
                    }
                }
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                backgroundColor: '#fff',
                titleFontColor: '#000',
                titleFontSize: 15,
                bodyFontColor: '#000',
                bodyFontSize: 15,
                borderColor: "#bbb",
                borderWidth: 1,
            }
        };
    
        return option;
    }

    /**
     * 折線圖資料物件
     * 
     * @param {Array} label 資料的名稱 ex:使用人數、停留時間...
     * @param {Array|String} color 線顏色
     * @param {Object} data 線資料物件
     * @returns {Object} 資料物件
     */
    line_datasets(label, color, data) {
        var datasets = {
            label: label,
            fill: false,
            tension: 0,
            pointRadius: 3,
            borderWidth: 2,
            backgroundColor: color,
            borderColor: color,
            datalabels: {
                // backgroundColor:'#1C84C6',
                color: color
            },
            data: data
        };
        return datasets;
    }

    /**
     * 折線圖顯示
     * 
     * @param {*} DOM_id 套用的元件ID
     * @param {Array} labels x軸分類
     * @param {Object} datasets 折線圖資料物件
     * @param {Object} options 折線圖option物件
     * @returns 圖表物件
     */
    line_chart(DOM_id, labels, datasets, options = this.line_options()) {
        var ctx = document.getElementById(DOM_id).getContext("2d");
        var ch = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: options
        });
        return ch;
    }

    /**
     * 長條圖options物件
     * 
     * @returns {Object} 設定物件
     */
    bar_options() {
        var options = {
            responsive: true,
            maintainAspectRatio: false,
            devicePixelRatio: 2,
            legend: {
                // align:'end',
                position: 'bottom',
            },
            layout: {
                padding: {
                    top: 30,
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        //stepSize: 2,
                        suggestedMax: 10
                    },
                }],
                xAxes: [{
                    angleLines: {
                        display: false
                    }
                }]
            },
            plugins: {
                datalabels: {
                    align: 'end',
                    anchor: 'end',
                }
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                backgroundColor: '#fff',
                titleFontColor: '#000',
                titleFontSize: 16,
                bodyFontColor: '#000',
                bodyFontSize: 16,
                borderColor: "#bbb",
                borderWidth: 1,
            }
        };
        return options;
    }

    /**
     * 長條圖資料物件
     * 
     * @param {Array} label 資料的名稱 ex:使用人數、停留時間...
     * @param {Array|String} color 長條顏色
     * @param {Object} data 長條資料物件
     * @returns {Object} 資料物件
     */
    bar_datasets(label, color, data) {
        var datasets = {
            label: label,
            fill: false,
            tension: 0,
            pointRadius: 5,
            backgroundColor: color,
            borderColor: color,
            datalabels: {
                // backgroundColor:'#1C84C6',
                color: color
            },
            data: data
        };
        return datasets;
    }

    /**
     * 長條圖顯示
     * 
     * @param {*} DOM_id 套用的元件ID
     * @param {Array} labels x軸分類
     * @param {Object} datasets 折線圖資料物件
     * @param {String} type 圖表樣式
     * @param {Object} options 折線圖option物件
     * @returns 圖表物件
     */
    bar_chart(DOM_id, labels, datasets, type='bar', options = this.bar_options()) {
        var ctx = document.getElementById(DOM_id).getContext("2d");
        var ch = new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: datasets
            },
            options: options
        });
        return ch;
    }

    /**
     * 圓餅圖資料物件
     * 
     * @param {Array} label 資料的名稱 ex:使用人數、停留時間...
     * @param {Array|String} color 長條顏色
     * @param {Object} data 長條資料物件
     * @returns {Object} 資料物件
     */
    pie_datasets(label, color, data) {
        var datasets = {
            label: label,
            backgroundColor: color,
            data: data
        };
    
        return datasets;
    }

    /**
     * 圓餅圖顯示
     * 
     * @param {*} DOM_id 套用的元件ID
     * @param {Array} labels x軸分類
     * @param {Object} datasets 折線圖資料物件
     * @param {String} type 圖表樣式
     * @returns 圖表物件
     */
    pie_chart(DOM_id, labels, datasets, type = 'pie') {
        var ctx = document.getElementById(DOM_id).getContext("2d");
        var ch = new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                devicePixelRatio: 2.5,
                legend: {
                    display: false,
                    // labels:{
                    //     generateLabels:function (chart) {
                    //         console.log(chart.data);
                    //     }
                    // }
                    // position: 'bottom',
                },
                legendCallback: function (chart) {
                    var txt = '';
                    for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
                        txt += `<li>
                                <i>
                                  <span style="background-color:${chart.data.datasets[0].backgroundColor[i]};"></span>
                                  ${chart.data.labels[i]}
                                 </i>
                                <b>${chart.data.datasets[0].data[i]}人</b>
                            </li>`;
                    }
                    return txt;
                },
                layout: {
                    padding: {
                        top: 20,
                        left: 20,
                        right: 20
                    }
                },
                // scales: {
                //     yAxes: [{
                //         ticks: {
                //             stepSize: 2,
                //             suggestedMax: 10
                //         }
                //     }]
                // },
                plugins: {
                    datalabels: {
                        color: '#fff',
                        align: 'center',
                        formatter: function (value, context) {
                            return value + '人';
                        },
                        display: function (context) {
    
                            var dataset = context.dataset;
                            var total = dataset.data.reduce((a, b) => parseInt(a) + parseInt(b));
                            //var count = dataset.data.length;
                            var value = dataset.data[context.dataIndex];
                            return value > total * 0.05;
                        }
                    },
    
                },
                tooltips: {
                    // mode:'index',
                    // intersect:false,
                    backgroundColor: '#fff',
                    titleFontColor: '#000',
                    titleFontSize: 15,
                    bodyFontColor: '#000',
                    bodyFontSize: 15,
                    borderColor: "#bbb",
                    borderWidth: 1,
                }
            }
        });
    
        return ch;
    }
}