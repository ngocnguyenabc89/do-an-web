<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
@include('admin.order.layout.order-title')

<!-- Page Body -->
<div class="row">
    {{-- Cột Bên trái --}}
    <div class="col-md-8">

        {{-- Chi Tiết Đơn Hàng --}}
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="text-center font-weight-bold mb-3">Giỏ Hàng</h5>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã Sản Phẩm</th>
                            <th>Ảnh</th>
                            <th>Tên Sản Phẩm</th>
                            <th class="text-right">Đơn Giá</th>
                            <th class="text-center">Số Lượng</th>
                            <th class="text-right font-weight-bold">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody id="table_body_order_detail">
                        <input type="number" name="order_id" hidden value="{{ $order->ma_don_hang}}">
                        @php
                        $number = 1
                        @endphp

                        @if ( isset($orderDetailList) )
                        @foreach($orderDetailList as $orderDetail)
                        <tr>
                            <td>{{ $number }}</td>
                            <td>
                                {{ $orderDetail->ma_san_pham }}
                                <input type="number" class="id_list" name="product_id_list[]" hidden
                                    value="{{ $orderDetail->ma_san_pham}}">
                            </td>
                            <td>
                                <image src="{{ asset("storage/product/$orderDetail->anh_san_pham") }}" alt="img"
                                    width="80">
                            </td>
                            <td>{{ $orderDetail->ten_san_pham }}</td>
                            <td class="text-primary text-right" id="{{ $orderDetail->ma_san_pham }}-price"
                                value="{{ $orderDetail->gia }}">
                                {{ $orderDetail->gia }}</td>
                            <td>
                                <input type="number" name="quantity_updated_list[]"
                                    id="{{ $orderDetail->ma_san_pham }}-quantity" min="0"
                                    class="form-control text-center text-center font-weight-bold"
                                    value={{ $orderDetail->so_luong_ban }} onchange="changeProductQuantity(this);"
                                    readonly>
                            </td>
                            <td class="text-primary font-weight-bold text-right amount"
                                id="{{ $orderDetail->ma_san_pham }}-amount" class="text-right"
                                value="{{ $orderDetail->thanh_tien }}">{{ $orderDetail->thanh_tien }}</td>
                        </tr>
                        @php
                        $number++
                        @endphp
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <p class="text-primary font-weight-bold text-right">Tổng Tiền</p>
                    </div>
                    <div class="col-md-2">
                        <p class="text-danger font-weight-bold text-right" id="amount_total"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tình Trạng Đơn Hàng -->
        <div class="card">
            <div class="card-body">
                <h5 class="text-center font-weight-bold mb-3">Nhân Viên</h5>

                {{-- form --}}
                <form method="POST" action="{{ url("admin/order/info/confirm/3") }}">
                    @csrf
                    <input type="number" name="order_id" value="{{ $order->ma_don_hang }}" hidden>

                    <!-- Ghi Chú -->
                    <div class=" row">
                        <div class="col-md-2">
                            <p>Ghi Chú:</p>
                        </div>
                        <div class="col-md-10">
                            <textarea name="user_note" class="font-weight-bold form-control" rows="5"
                                readonly>{{ $order->ghi_chu_nhan_vien }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-5 mt-5 d-flex justify-content-center">
                        <a href="{{ url("admin/order/list") }}" class="btn btn-secondary mr-5">Quay Lại</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        {{-- Thông Tin Khách Hàng - Cột bên phải --}}
        @include('admin.order.layout.order-customer')

        <!-- Lịch Sử -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="text-center font-weight-bold mb-3">Lịch Sử</h5>
                <textarea name="order_history" class=" form-control" rows="15" readonly>{{ $order->lich_su }}</textarea>
            </div>
        </div>
    </div>
    <!-- kết thúc main-container -->
    @endsection
    {{-- Javascript --}}
    @section('script')
    <script>
        // Kiểm tra biến errors từ server gửi về. Nếu có lỗi xuất popup thông báo
        @if(count($errors) > 0)
        Swal.fire({
            title: 'Thất Bại',
            text: 'Vui lòng kiểm tra lại thông tin',
            icon: 'error',
            confirmButtonText: 'OK'
        })
        @endif

        // Change Product Quantity
        const changeProductQuantity = (inputQuantity) => {
            const productId = inputQuantity.id.split("-")[0];
            const productPrice = document.getElementById(`${productId}-price`).getAttribute('value');
            const productQuantity = inputQuantity.value

            const amount_dom = document.getElementById(`${productId}-amount`)

            let amount = parseInt(productPrice) * parseInt(productQuantity);

            if (isNaN(amount)) {
                amount = 0;
            }
            amount_dom.innerHTML = amount
            amount_dom.setAttribute('value', amount)

            getTotalAmount()
        }

        // Get total amount
        const getTotalAmount = () => {
            const amount_total_dom = document.querySelector('#amount_total')
            const amount_list_dom = document.querySelectorAll('.amount');
            const amount_list = Array.from(amount_list_dom);
            console.log(amount_list);

            let amount_total = 0;

            amount_list.forEach((amount) => {
                amount_total += parseInt(amount.getAttribute('value'))
            })

            console.log(amount_total)
            amount_total_dom.innerHTML = amount_total
        }
        getTotalAmount();

        // Add Product
        const addProduct = (btnAdd) => {
            const table_body_order_detail_dom = document.querySelector('#table_body_order_detail')
            const product_id = btnAdd.id.split('-')[0]
            const product_image = document.getElementById(`${product_id}-add_product_image`).src;
            const product_name = document.getElementById(`${product_id}-add_product_name`).getAttribute('value')
            const product_price = document.getElementById(`${product_id}-add_product_price`).getAttribute('value')

            const id_list_dom = document.querySelectorAll('.id_list')
            const id_list = Array.from(id_list_dom)
            console.log(id_list)
            let number = id_list.length
            for (let i = 0; i < number; i++) {
                console.log(id_list[i])
                if (product_id == id_list[i].value) {
                    return
                }
            }

            let tr_html = ` <tr>
                                <td>%number%</td>
                                <td>
                                    %product_id%
                                    <input type="number" class="id_list" name="product_id_list[]" hidden
                                        value="%product_id%">
                                </td>
                                <td>
                                    <image src="%product_image%" alt="img"
                                        width="80">
                                </td>
                                <td>%product_name%</td>
                                <td class="text-primary text-right" id="%product_id%-price"
                                    value="%product_price%">
                                    %product_price%</td>
                                <td>
                                    <input type="number" name="quantity_updated_list[]"
                                        id="%product_id%-quantity" min="0"
                                        class="form-control text-center text-center font-weight-bold"
                                        value="0" onchange="changeProductQuantity(this);">
                                </td>
                                <td class="text-primary font-weight-bold text-right amount"
                                    id="%product_id%-amount" class="text-right"
                                    value="0">0</td>
                            </tr>`
            tr_html = tr_html.replaceAll('%number%', ++number)
            tr_html = tr_html.replaceAll('%product_id%', product_id)
            tr_html = tr_html.replaceAll('%product_name%', product_name)
            tr_html = tr_html.replaceAll('%product_price%', product_price)
            tr_html = tr_html.replaceAll('%product_image%', product_image)

            table_body_order_detail_dom.insertAdjacentHTML('beforeend', tr_html)
        }
    </script>
    @endsection