/*
 * ymax: 柱状图阴影部分的高度(如果数值高于左边的数字，左边的数字也会增长)
 * dataAxis：柱状图上的显示的文字(一维数组)
 * data：柱状图上的数值(一维数组)
 * id：div的id名
 * title:图表中的标题
 * */
function zhuzhuangtu(yMax,data,dataAxis,id,title){

    var dom = document.getElementById(id);
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    // var dataAxis = dataAxis;
    // var data = data;
    // var yMax = yMax;
    var dataShadow = [];

    for (var i = 0; i < data.length; i++) {
        dataShadow.push(yMax);
    }

    option = {
    title: {
        text: title,// '特性示例：渐变色 阴影 点击缩放',
        x   : 'center'
        //subtext: 'Feature Sample: Gradient Color, Shadow, Click Zoom'
    },
        xAxis: {
            data: dataAxis,
            axisLabel: {
                inside: true,
                textStyle: {
                    color: '#fff'
                }
            },
            axisTick: {
                show: false
            },
            axisLine: {
                show: false
            },
            z: 10
        },
        yAxis: {
            axisLine: {
                show: false
            },
            axisTick: {
                show: false
            },
            axisLabel: {
                textStyle: {
                    color: '#999'
                }
            }
        },
        dataZoom: [
            {
                type: 'inside'
            }
        ],
        series: [
            { // For shadow
                type: 'bar',
                itemStyle: {
                    normal: {color: 'rgba(0,0,0,0.05)'}
                },
                barGap:'-100%',
                barCategoryGap:'40%',
                data: dataShadow,
                animation: false
            },
            {
                type: 'bar',
                itemStyle: {
                    normal: {
                        color: new echarts.graphic.LinearGradient(
                            0, 0, 0, 1,
                            [
                                {offset: 0, color: '#83bff6'},
                                {offset: 0.5, color: '#188df0'},
                                {offset: 1, color: '#188df0'}
                            ]
                        )
                    },
                    emphasis: {
                        color: new echarts.graphic.LinearGradient(
                            0, 0, 0, 1,
                            [
                                {offset: 0, color: '#2378f7'},
                                {offset: 0.7, color: '#2378f7'},
                                {offset: 1, color: '#83bff6'}
                            ]
                        )
                    }
                },
                data: data
            }
        ]
    };

    // Enable data zoom when user click bar.
    var zoomSize = 6;
    myChart.on('click', function (params) {
        console.log(params);
        console.log('data');
        console.log(data);
        console.log(data.length);
        let startValue = dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)];
        let endValue = dataAxis[Math.min(params.dataIndex + zoomSize / 2, data.length - 1)];
        console.log('startValue',startValue);
        console.log('endValue',endValue);
        myChart.dispatchAction({
            type: 'dataZoom',
            startValue: startValue,
            endValue: endValue
        });
    });
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
}
/**
 * Created by Administrator on 2018/9/15.
 */

/*
 * id:div中id的值
 * title：图表中正上方的名称
 * data：饼状图中的名称和数据
 * leftTitle: 右上角标识的显示的名称
 * */
function bingzhuangtu(id,title,data,leftTitle){
    var dom = document.getElementById(id);
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    option = {
        title : {
            text: title,
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'right',
            data: leftTitle
        },
        series : [
            {
                name: '访问来源',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:data,
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
}