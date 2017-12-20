@extends('layouts.base')

@section('title')
    Company Report
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.bundle.js"></script>
    <script>
        var randomScalingFactor = function () {
            return Math.round(Math.random() * 100);
        };

        function randomColor() {
            var r = Math.floor(Math.random() * 256);
            var g = Math.floor(Math.random() * 256);
            var b = Math.floor(Math.random() * 256);
            return "rgb(" + r + ',' + g + ',' + b + ")";
        }

        var data = [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()];
        var backgroundColor = [];
        var name = ['red', 'blue']
        var labels = [];
        var sum = 0;
        // sum直接等于总股数
        // 下面这个循环就先用着到时候删掉
        for (var i = 0; i < data.length; i++) {
            sum += data[i];
        }
        //
        for (var i = 0; i < data.length; i++) {
            backgroundColor.push(randomColor());
        }

        for (var i = 0; i < name.length; i++) {
            labels.push(name[i] + "(" + ((data[i] / sum) * 100).toFixed(2) + "%)");
        }

        function information() {
            $.ajax({
                url: "wtfFrontEndIsShit.php",
                dataType: "json",
                type: "GET",
                success: function (msg) {
                    for (var i = 0; i < msg.length; i++) {
                        for (var j = 0; j < msg[i].length; j++) {
                            data[j] = msg[i][j];
                            name[j] = msg[i][j];
                        }
                    }
                },
                error: function () {

                }
            })
        }

        $(document).ready(function () {
            setTimeout("information()", 5000);
        });

        var config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColor,
                    label: 'Dataset 1'
                }],
                labels: labels
            },
            options: {
                responsive: true
            }
        };

        window.onload = function () {
            var ctx = document.getElementById("chart-area").getContext("2d");
            window.myPie = new Chart(ctx, config);
        };


        function createDom() {
            $.each(receivedInfo, function () {
            });
            $('#table').html(html);
        };

    </script>
@endsection

@section('stylesheet')
    <style>
        .doc-container {
            padding-top: 30px;
            padding-bottom: 150px;
        }

        .mdui-tab {
            min-width: 40px;
        !important;
        }

        .company {
            padding: 10px;
        !important;
        }
    </style>
@endsection

@section('body')
    <div class="mdui-container doc-container">
        <div class="mdui-tab mdui-tab-scrollable" mdui-tab>
            <a class="mdui-ripple">1</a>
            <a class="mdui-ripple">2</a>
            <a class="mdui-ripple">3</a>
            <a class="mdui-ripple">1</a>
            <a class="mdui-ripple">2</a>
            <a class="mdui-ripple">3</a>
            <a class="mdui-ripple">1</a>
            <a class="mdui-ripple">2</a>
            <a class="mdui-ripple">3</a>
            <a class="mdui-ripple">1</a>
            <a class="mdui-ripple">2</a>
            <a class="mdui-ripple">3</a>
        <!--@foreach ($years as $year)-->
            <!--<a href="#example2-tab6" class="mdui-ripple">maps</a>-->
        <!--<li>{{ $user->name }}</li>-->
            <!--@endforeach-->
        </div>
        </br></br>
        <div class="mdui-card-header">
            <div class="mdui-typo-display-1 mdui-text-center mdui-text-color-theme">
                Financial Report
            </div>
        </div>
        <div class="mdui-col-md-12">
            <div class="company mdui-col-md-12">
                <div class="mdui-card">
                    <div class="mdui-card-primary">
                        <div class="mdui-card-primary-title">CompanyName1</div>
                    </div>
                    <div class="mdui-card-content">
                        <div id="canvas-holder">
                            <canvas id="chart-area"/>
                        </div>
                        <ul class="mdui-list">
                            <li class="mdui-list-item mdui-ripple">总股数：</li>
                            <li class="mdui-list-item mdui-ripple">股价：</li>
                            <li class="mdui-list-item mdui-ripple">去年的利润：</li>
                            <li class="mdui-list-item mdui-ripple">公司持有的建筑：</li>
                            <li class="mdui-list-item mdui-ripple">分红率：</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="company mdui-col-md-6">
                <div class="mdui-card">
                    <div class="mdui-card-primary">
                        <div class="mdui-card-primary-title">CompanyName2</div>
                    </div>
                </div>
            </div>
            <div class="company mdui-col-md-6">
                <div class="mdui-card">
                    <div class="mdui-card-primary">
                        <div class="mdui-card-primary-title">CompanyName3</div>
                    </div>
                </div>
            </div>
            <div class="company mdui-col-md-6">
                <div class="mdui-card">
                    <div class="mdui-card-primary">
                        <div class="mdui-card-primary-title">CompanyName4</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
