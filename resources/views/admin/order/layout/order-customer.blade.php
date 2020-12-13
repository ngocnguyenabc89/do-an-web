<div class="card">
    <div class="card-body">
        <h5 class="text-center font-weight-bold mb-3">Khách Hàng</h5>
        <form action="{{ url('admin/order/info/update-customer') }}" method="POST">
            <input type="number" name="order_id" value="{{ $order->ma_don_hang }}" hidden>
            @csrf
            <div class="row mb-3">
                <p class="col-md-4">Họ Tên:</p>
                <input type="text" name="customer_name" class="col-md-8 form-control font-weight-bold"
                    value="{{ $order->ten_khach_hang }}" required readonly>
            </div>
            <div class="row mb-3">
                <p class="col-md-4">Điện Thoại:</p>
                <input type="text" name="customer_phone" class="col-md-8 form-control font-weight-bold"
                    value="{{ $order->dien_thoai_khach_hang }}" required readonly>
            </div>
            <div class="row mb-3">
                <p class="col-md-4">Địa Chỉ:</p>
                <textarea name="customer_address" class="col-md-8 font-weight-bold form-control" cols="30" rows="4"
                    required readonly>{{ $order->dia_chi_giao_hang }}</textarea>
            </div>
            <div class="row mb-3">
                <p class="col-md-4">Ngày Giao:</p>
                <input type="datetime-local" name="customer_time_delivery" class="form-control col-md-8"
                    id="customer_time"
                    value="{{ date("Y-m-d", strtotime($order->thoi_gian_giao_hang))."T".date("H:m", strtotime($order->thoi_gian_giao_hang)) }}"
                    required readonly>
            </div>
            <div class="row mb-3">
                <p class="col-md-4">Ghi Chú:</p>
                <textarea name="customer_note" class="col-md-8 font-weight-bold form-control" cols="30" rows="4"
                    readonly>{{ $order->ghi_chu_khach_hang }}</textarea>
            </div>
        </form>
    </div>
</div>