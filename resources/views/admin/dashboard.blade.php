<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-primary font-weight-bold">Dashboard</h1>
</div>

<!-- Page Body -->
<div class="card">
	<div class="card-body">
		<!-- Content Row -->
		<div class="row">

			<!-- Doanh Thu Năm -->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-danger shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
									Doanh Thu Năm {{ date("Y") }}</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									{{ number_format($earn_this_year, 0, '', ',') }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>


			<!-- Doanh Thu Tháng Này -->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-success shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
									Doanh Thu Tháng {{ date("m/Y") }}</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									{{ number_format($earn_this_month, 0, '', ',') }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>



			<!-- Số lượng bán ra tháng này -->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-info shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Số Lượng
									Bán Ra Tháng {{ date("m/Y") }}
								</div>
								<div class="row no-gutters align-items-center">
									<div class="col-auto">
										<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
											{{ $number_product_sale }}</div>
									</div>
								</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-boxes fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Đơn Hàng Đang Chờ Xác Nhận -->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-primary shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
									Đơn Hàng Chờ Xác Nhận</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $number_order_pending }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Content Row -->

		<div class="row">

			<!-- Area Chart -->
			<div class="col-xl-8 col-lg-7">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">Biểu Đồ Doanh Thu Năm {{ date('Y') }} (VND)</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="chart-area">
							<canvas id="myAreaChart"></canvas>
						</div>
					</div>
				</div>
			</div>

			<!-- Pie Chart -->
			<div class="col-xl-4 col-lg-5">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">Tình Trạng Đơn Hàng Năm {{ date('Y') }} (%)</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="chart-pie pt-4 pb-2">
							<canvas id="myPieChart"></canvas>
						</div>
						<div class="mt-4 text-center small">
							<span class="mr-2">
								<i class="fas fa-circle text-danger"></i> Hủy
							</span>
							<span class="mr-2">
								<i class="fas fa-circle text-warning"></i> Chờ xác nhận
							</span>
							<span class="mr-2">
								<i class="fas fa-circle text-primary"></i> Đã xác nhận
							</span>
							<span class="mr-2">
								<i class="fas fa-circle text-success"></i> Thành Công
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- kết thúc main-container -->
@endsection

<!-- JS -->
@section('script')
<script>
	// BIỂU ĐỒ DOANH THU
	// Set new default font family and font color to mimic Bootstrap's default styling
	Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#858796';

	function number_format(number, decimals, dec_point, thousands_sep) {
		// *     example: number_format(1234.56, 2, ',', ' ');
		// *     return: '1 234,56'
		number = (number + '').replace(',', '').replace(' ', '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function(n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};
		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
	}

	const earn_month_list = [];
	@foreach($earn_month_list as $earn_month)
	earn_month_list.push({{ $earn_month }});
	@endforeach

	console.log(earn_month_list);
	// Area Chart Example
	var ctx = document.getElementById("myAreaChart");
	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
			datasets: [{
				label: "Doanh Thu",
				lineTension: 0.3,
				backgroundColor: "rgba(78, 115, 223, 0.05)",
				borderColor: "rgba(78, 115, 223, 1)",
				pointRadius: 3,
				pointBackgroundColor: "rgba(78, 115, 223, 1)",
				pointBorderColor: "rgba(78, 115, 223, 1)",
				pointHoverRadius: 3,
				pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
				pointHoverBorderColor: "rgba(78, 115, 223, 1)",
				pointHitRadius: 10,
				pointBorderWidth: 2,
				data: earn_month_list,
			}],
		},
		options: {
			maintainAspectRatio: false,
			layout: {
				padding: {
					left: 10,
					right: 25,
					top: 25,
					bottom: 0
				}
			},
			scales: {
				xAxes: [{
					time: {
						unit: 'date'
					},
					gridLines: {
						display: false,
						drawBorder: false
					},
					ticks: {
						maxTicksLimit: 7
					}
				}],
				yAxes: [{
					ticks: {
						maxTicksLimit: 5,
						padding: 10,
						// Include a dollar sign in the ticks
						callback: function(value, index, values) {
							return number_format(value);
						}
					},
					gridLines: {
						color: "rgb(234, 236, 244)",
						zeroLineColor: "rgb(234, 236, 244)",
						drawBorder: false,
						borderDash: [2],
						zeroLineBorderDash: [2]
					}
				}],
			},
			legend: {
				display: false
			},
			tooltips: {
				backgroundColor: "rgb(255,255,255)",
				bodyFontColor: "#858796",
				titleMarginBottom: 10,
				titleFontColor: '#6e707e',
				titleFontSize: 14,
				borderColor: '#dddfeb',
				borderWidth: 1,
				xPadding: 15,
				yPadding: 15,
				displayColors: false,
				intersect: false,
				mode: 'index',
				caretPadding: 10,
				callbacks: {
					label: function(tooltipItem, chart) {
						var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
						return datasetLabel + " " + number_format(tooltipItem.yLabel) + " VND";
					}
				}
			}
		}
	});



	// BIỂU ĐỒ TÌNH TRẠNG ĐƠN HÀNG

	let order_percent_list = []
	@foreach($order_percent_list as $order_percent)
	order_percent_list.push( {{ $order_percent }} );
	@endforeach

	console.log(order_percent_list)

	// Set new default font family and font color to mimic Bootstrap's default styling
	Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#858796';

	// Pie Chart Example
	var ctx = document.getElementById("myPieChart");
	var myPieChart = new Chart(ctx, {
		type: 'doughnut',
		data: {
			labels:['Hủy', 'Chờ xác nhận', 'Đã xác nhận', 'Thành công'],
			datasets: [{
				data:  order_percent_list,
				backgroundColor: ['#e74a3b', '#f6c23e', '#4e73df', '#1cc88a'],
				hoverBackgroundColor: ['#e02d1b', '#f4b619', '#2e59d9', '#1cc88a'],
				hoverBorderColor: "rgba(234, 236, 244, 1)",
			}],
		},
		options: {
			maintainAspectRatio: false,
			tooltips: {
				backgroundColor: "rgb(255,255,255)",
				bodyFontColor: "#858796",
				borderColor: '#dddfeb',
				borderWidth: 1,
				xPadding: 15,
				yPadding: 15,
				displayColors: false,
				caretPadding: 10,
			},
			legend: {
				display: false
			},
			cutoutPercentage: 80,
		},
	});
</script>
@endsection