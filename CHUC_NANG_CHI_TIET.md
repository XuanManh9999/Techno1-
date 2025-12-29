# TÀI LIỆU MÔ TẢ CHỨC NĂNG CHI TIẾT - HỆ THỐNG E-COMMERCE TECHNO1

## MỤC LỤC
1. [Tổng quan hệ thống](#tong-quan)
2. [Chức năng dành cho khách hàng](#khach-hang)
3. [Chức năng dành cho quản trị viên](#quan-tri)
4. [Cấu trúc dữ liệu](#cau-truc-du-lieu)
5. [API và tích hợp](#api-tich-hop)
6. [Bảo mật và phân quyền](#bao-mat)
7. [Giao diện và UX](#giao-dien)

---

## 1. TỔNG QUAN HỆ THỐNG {#tong-quan}

### 1.1. Mô tả chung
Hệ thống E-commerce Techno1 là một website bán hàng trực tuyến chuyên về trang thiết bị điện tử, được xây dựng bằng PHP Laravel Framework. Hệ thống hỗ trợ đầy đủ các chức năng từ quản lý sản phẩm, đặt hàng, thanh toán đến quản trị hệ thống.

### 1.2. Công nghệ sử dụng
- **Backend**: PHP 8.1+, Laravel Framework
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Editor**: TinyMCE 6 (Rich Text Editor)
- **Payment Gateway**: VNPAY
- **Timezone**: Asia/Ho_Chi_Minh

### 1.3. Đối tượng sử dụng
- **Khách hàng**: Người dùng cuối mua sắm sản phẩm
- **Quản trị viên**: Quản lý toàn bộ hệ thống

---

## 2. CHỨC NĂNG DÀNH CHO KHÁCH HÀNG {#khach-hang}

### 2.1. Trang chủ (Homepage)
**Route**: `/`
- **Hiển thị sản phẩm nổi bật**: Hiển thị 8 sản phẩm được đánh dấu là "featured"
- **Sản phẩm mới nhất**: Hiển thị 8 sản phẩm mới được thêm vào hệ thống
- **Danh mục sản phẩm**: Hiển thị các danh mục với số lượng sản phẩm trong mỗi danh mục
- **Giao diện responsive**: Tự động điều chỉnh theo kích thước màn hình

### 2.2. Danh sách sản phẩm
**Route**: `/products`
- **Hiển thị danh sách**: Hiển thị tất cả sản phẩm với phân trang (pagination)
- **Tìm kiếm**: Tìm kiếm theo tên sản phẩm, SKU, mô tả
- **Lọc sản phẩm**:
  - Theo danh mục (Category)
  - Theo thương hiệu (Brand)
  - Theo khoảng giá (min_price, max_price)
- **Sắp xếp**: 
  - Mới nhất
  - Giá tăng dần
  - Giá giảm dần
  - Tên A-Z
- **Hiển thị thông tin**:
  - Hình ảnh sản phẩm
  - Tên sản phẩm
  - Giá gốc và giá khuyến mãi (nếu có)
  - Danh mục và thương hiệu
  - Trạng thái tồn kho

### 2.3. Chi tiết sản phẩm
**Route**: `/products/{slug}`
- **Thông tin cơ bản**:
  - Tên sản phẩm
  - Hình ảnh sản phẩm (hỗ trợ URL)
  - Giá gốc và giá khuyến mãi
  - SKU
  - Mô tả ngắn và mô tả chi tiết (HTML)
  - Danh mục và thương hiệu
- **Biến thể sản phẩm (Product Variants)**:
  - Chọn màu sắc (Màu sắc)
  - Chọn kích thước/Dung lượng (Kích thước)
  - Hiển thị giá và số lượng tồn kho theo từng biến thể
  - Hiển thị hình ảnh biến thể (nếu có)
  - Trạng thái active khi chọn biến thể
  - Cập nhật giá, SKU, số lượng tồn kho theo biến thể được chọn
- **Chức năng**:
  - Chọn số lượng
  - Thêm vào giỏ hàng (yêu cầu đăng nhập)
  - Hiển thị trạng thái tồn kho
  - Hiển thị giá min/max nếu có nhiều biến thể

### 2.4. Giỏ hàng (Shopping Cart)
**Route**: `/cart`
- **Hiển thị giỏ hàng**:
  - Danh sách sản phẩm trong giỏ hàng
  - Thông tin chi tiết:
    - Hình ảnh sản phẩm/biến thể
    - Tên sản phẩm và biến thể (hiển thị dạng badges)
    - SKU
    - Giá đơn vị
    - Số lượng
    - Thành tiền
  - Tổng tiền tạm tính
  - Phí vận chuyển (hiện tại: Miễn phí)
  - Tổng cộng
- **Quản lý giỏ hàng**:
  - Cập nhật số lượng
  - Xóa sản phẩm khỏi giỏ hàng
  - Xóa toàn bộ giỏ hàng
- **Điều hướng**:
  - Tiếp tục mua sắm
  - Thanh toán (chuyển đến trang checkout)

### 2.5. Thanh toán (Checkout)
**Route**: `/cart/checkout`
- **Thông tin giao hàng**:
  - Họ và tên (tự động điền từ thông tin user)
  - Số điện thoại (tự động điền từ thông tin user)
  - Chọn địa chỉ:
    - Tỉnh/Thành phố (dropdown động)
    - Quận/Huyện (dropdown động, phụ thuộc Tỉnh)
    - Phường/Xã (dropdown động, phụ thuộc Quận)
    - Địa chỉ chi tiết (số nhà, tên đường)
    - Địa chỉ đầy đủ (tự động điền khi chọn Tỉnh/Quận/Phường)
  - Ghi chú (tùy chọn)
- **Tóm tắt đơn hàng**:
  - Danh sách sản phẩm với hình ảnh, tên, biến thể, số lượng, giá
  - Mã giảm giá:
    - Input nhập mã giảm giá
    - Nút "Áp dụng" để validate mã
    - Hiển thị thông báo thành công/lỗi
    - Hiển thị số tiền được giảm
    - Có thể xóa mã giảm giá đã áp dụng
  - Tạm tính
  - Giảm giá (nếu có)
  - Phí vận chuyển
  - Tổng cộng
- **Phương thức thanh toán**:
  - Thanh toán VNPAY (mặc định)
  - Thanh toán khi nhận hàng (COD)
- **Xác nhận đặt hàng**: Tạo đơn hàng và chuyển đến trang thanh toán

### 2.6. Đơn hàng (Orders)
**Route**: `/orders`
- **Danh sách đơn hàng**:
  - Hiển thị tất cả đơn hàng của khách hàng
  - Thông tin đơn hàng:
    - Mã đơn hàng (order_number)
    - Ngày đặt hàng
    - Tổng tiền
    - Trạng thái đơn hàng (pending, processing, shipped, delivered, cancelled)
    - Trạng thái thanh toán (pending, paid, failed)
    - Phương thức thanh toán
  - Phân trang
- **Chi tiết đơn hàng**:
  **Route**: `/orders/{id}`
  - Thông tin đơn hàng đầy đủ
  - Danh sách sản phẩm trong đơn hàng
  - Thông tin giao hàng
  - Lịch sử cập nhật trạng thái
  - Nút thanh toán (nếu chưa thanh toán)

### 2.7. Thanh toán (Payment)
**Route**: `/payment/{orderId}`
- **Tạo giao dịch VNPAY**:
  - Tạo URL thanh toán VNPAY
  - Chuyển hướng đến cổng thanh toán VNPAY
- **Xử lý kết quả thanh toán**:
  **Route**: `/payment/return`, `/vnpay/return`
  - Nhận callback từ VNPAY
  - Xác thực chữ ký
  - Cập nhật trạng thái thanh toán
  - Hiển thị kết quả thanh toán

### 2.8. Hồ sơ cá nhân (Profile)
**Route**: `/profile`
- **Thông tin cá nhân**:
  - Họ và tên
  - Email
  - Số điện thoại
  - Địa chỉ
  - Cập nhật thông tin
- **Đổi mật khẩu**:
  **Route**: `/profile/change-password`
  - Mật khẩu hiện tại
  - Mật khẩu mới
  - Xác nhận mật khẩu mới

### 2.9. Blog
**Route**: `/blog`
- **Danh sách bài viết**:
  - Hiển thị các bài viết đã được xuất bản
  - Phân trang
  - Hiển thị:
    - Hình ảnh đại diện
    - Tiêu đề
    - Mô tả ngắn (excerpt)
    - Ngày xuất bản
    - Số lượt xem
    - Tác giả
- **Chi tiết bài viết**:
  **Route**: `/blog/{slug}`
  - Tiêu đề
  - Hình ảnh đại diện
  - Nội dung đầy đủ (HTML)
  - Thông tin tác giả
  - Ngày xuất bản
  - Số lượt xem (tự động tăng khi xem)

### 2.10. Xác thực (Authentication)
- **Đăng ký**:
  **Route**: `/register`
  - Họ và tên
  - Email
  - Mật khẩu
  - Xác nhận mật khẩu
  - Số điện thoại
  - Địa chỉ
- **Đăng nhập**:
  **Route**: `/login`
  - Email
  - Mật khẩu
  - Nhớ đăng nhập (Remember me)
- **Đăng xuất**:
  **Route**: `/logout`
- **Quên mật khẩu**:
  **Route**: `/password/reset`
  - Nhập email
  - Gửi link reset password qua email
  - Đặt lại mật khẩu mới

---

## 3. CHỨC NĂNG DÀNH CHO QUẢN TRỊ VIÊN {#quan-tri}

### 3.1. Dashboard (Bảng điều khiển)
**Route**: `/admin/dashboard`
- **Thống kê tổng quan**:
  - Tổng số đơn hàng
  - Số đơn hàng đang chờ xử lý
  - Tổng doanh thu (từ các đơn hàng đã thanh toán)
  - Tổng số sản phẩm
  - Tổng số khách hàng
- **Đơn hàng gần đây**: 10 đơn hàng mới nhất
- **Doanh thu theo tháng**: Biểu đồ doanh thu theo từng tháng

### 3.2. Quản lý sản phẩm (Products)
**Route**: `/admin/products`
- **Danh sách sản phẩm**:
  - Hiển thị tất cả sản phẩm với phân trang
  - Tìm kiếm: theo tên, SKU, mô tả
  - Lọc: theo danh mục, thương hiệu, trạng thái
  - Sắp xếp: theo các tiêu chí khác nhau
  - Hiển thị: hình ảnh, tên, SKU, giá, số lượng tồn kho, trạng thái
- **Thêm sản phẩm mới**:
  **Route**: `/admin/products/create`
  - **Thông tin cơ bản**:
    - Tên sản phẩm (bắt buộc)
    - Mô tả ngắn
    - Mô tả chi tiết (Rich Text Editor - TinyMCE):
      - Hỗ trợ định dạng văn bản (bold, italic, underline)
      - Danh sách (bulleted, numbered)
      - Links
      - Hình ảnh
      - Tables
      - Code blocks
      - Alignment
    - Ảnh sản phẩm (URL) với preview
    - Danh mục (bắt buộc)
    - Thương hiệu (tùy chọn)
    - SKU (bắt buộc, unique)
    - Giá (bắt buộc)
    - Giá khuyến mãi (tùy chọn)
    - Số lượng tồn kho (bắt buộc)
    - Trạng thái (Hiển thị/Không hiển thị)
    - Sản phẩm nổi bật (Featured)
  - **Biến thể sản phẩm (Product Variants)**:
    - Thêm/xóa biến thể động
    - Mỗi biến thể có:
      - Màu sắc (Màu sắc)
      - Kích thước/Dung lượng (Kích thước)
      - SKU (tự động tạo nếu để trống)
      - Giá (tùy chọn, dùng giá sản phẩm nếu để trống)
      - Giá khuyến mãi (tùy chọn)
      - Số lượng tồn kho
      - Ảnh biến thể (URL)
      - Mặc định (chỉ một biến thể có thể là mặc định)
      - Trạng thái (Kích hoạt/Vô hiệu)
- **Sửa sản phẩm**:
  **Route**: `/admin/products/{id}/edit`
  - Tương tự như thêm sản phẩm
  - Hiển thị và chỉnh sửa các biến thể hiện có
  - Có thể thêm/xóa/sửa biến thể
- **Xóa sản phẩm**:
  - Xác nhận trước khi xóa
  - Xóa cả các biến thể liên quan

### 3.3. Quản lý danh mục (Categories)
**Route**: `/admin/categories`
- **Danh sách danh mục**:
  - Hiển thị tất cả danh mục với phân trang
  - Tìm kiếm theo tên
  - Lọc theo trạng thái
  - Hiển thị: tên, mô tả, trạng thái, số lượng sản phẩm
- **Thêm danh mục**:
  - Tên danh mục (bắt buộc)
  - Mô tả
  - Trạng thái (Hiển thị/Không hiển thị)
- **Sửa danh mục**: Tương tự thêm danh mục
- **Xóa danh mục**: Xác nhận trước khi xóa

### 3.4. Quản lý thương hiệu (Brands)
**Route**: `/admin/brands`
- **Danh sách thương hiệu**:
  - Hiển thị tất cả thương hiệu với phân trang
  - Tìm kiếm theo tên
  - Lọc theo trạng thái
  - Hiển thị: tên, mô tả, trạng thái, số lượng sản phẩm
- **Thêm thương hiệu**:
  - Tên thương hiệu (bắt buộc)
  - Mô tả
  - Trạng thái (Hiển thị/Không hiển thị)
- **Sửa thương hiệu**: Tương tự thêm thương hiệu
- **Xóa thương hiệu**: Xác nhận trước khi xóa

### 3.5. Quản lý mã giảm giá (Coupons)
**Route**: `/admin/coupons`
- **Danh sách mã giảm giá**:
  - Hiển thị tất cả mã giảm giá với phân trang
  - Tìm kiếm: theo mã, tên
  - Lọc: theo loại (percentage/fixed), trạng thái, ngày hết hạn
  - Hiển thị:
    - Mã giảm giá
    - Tên
    - Loại (Phần trăm/Cố định)
    - Giá trị
    - Số lần sử dụng (used_count/usage_limit)
    - Ngày bắt đầu/kết thúc
    - Trạng thái
- **Thêm mã giảm giá**:
  - Mã giảm giá (bắt buộc, unique)
  - Tên
  - Mô tả
  - Loại: Phần trăm (%) hoặc Cố định (VNĐ)
  - Giá trị (bắt buộc)
  - Giá trị đơn hàng tối thiểu (tùy chọn)
  - Giá trị giảm tối đa (tùy chọn, chỉ áp dụng cho loại phần trăm)
  - Giới hạn số lần sử dụng (tùy chọn)
  - Giới hạn số lần sử dụng mỗi user (tùy chọn)
  - Ngày bắt đầu (tùy chọn)
  - Ngày hết hạn (tùy chọn)
  - Trạng thái (Kích hoạt/Vô hiệu)
- **Sửa mã giảm giá**: Tương tự thêm mã giảm giá
- **Xóa mã giảm giá**: Xác nhận trước khi xóa

### 3.6. Quản lý đơn hàng (Orders)
**Route**: `/admin/orders`
- **Danh sách đơn hàng**:
  - Hiển thị tất cả đơn hàng với phân trang
  - Tìm kiếm: theo mã đơn hàng, tên khách hàng, email, số điện thoại
  - Lọc: theo trạng thái đơn hàng, trạng thái thanh toán, ngày đặt hàng
  - Hiển thị:
    - Mã đơn hàng
    - Khách hàng
    - Tổng tiền
    - Trạng thái đơn hàng
    - Trạng thái thanh toán
    - Ngày đặt hàng
- **Chi tiết đơn hàng**:
  **Route**: `/admin/orders/{id}`
  - Thông tin đơn hàng đầy đủ
  - Thông tin khách hàng
  - Danh sách sản phẩm trong đơn hàng
  - Thông tin giao hàng
  - Thông tin thanh toán
  - Mã giảm giá đã sử dụng (nếu có)
  - Lịch sử cập nhật trạng thái
- **Cập nhật trạng thái đơn hàng**:
  **Route**: `/admin/orders/{id}/status`
  - Các trạng thái:
    - Pending (Chờ xử lý)
    - Processing (Đang xử lý)
    - Shipped (Đã giao hàng)
    - Delivered (Đã nhận hàng)
    - Cancelled (Đã hủy)

### 3.7. Quản lý người dùng (Users)
**Route**: `/admin/users`
- **Danh sách người dùng**:
  - Hiển thị tất cả người dùng với phân trang
  - Tìm kiếm: theo tên, email
  - Lọc: theo vai trò (admin/customer)
  - Hiển thị: tên, email, vai trò, ngày đăng ký
- **Xem chi tiết người dùng**: Thông tin đầy đủ của người dùng
- **Xóa người dùng**: Xác nhận trước khi xóa

### 3.8. Quản lý bài viết (Posts)
**Route**: `/admin/posts`
- **Danh sách bài viết**:
  - Hiển thị tất cả bài viết với phân trang
  - Tìm kiếm: theo tiêu đề
  - Lọc: theo trạng thái xuất bản
  - Hiển thị: tiêu đề, tác giả, trạng thái, ngày xuất bản, số lượt xem
- **Thêm bài viết mới**:
  **Route**: `/admin/posts/create`
  - Tiêu đề (bắt buộc)
  - Slug (tự động tạo từ tiêu đề nếu để trống)
  - Mô tả ngắn (excerpt)
  - Hình ảnh đại diện (upload file)
  - Nội dung (Rich Text Editor - TinyMCE):
    - Hỗ trợ đầy đủ định dạng văn bản
    - Chèn hình ảnh (upload qua API)
    - Links, tables, code blocks
  - Ngày xuất bản (tùy chọn)
  - Trạng thái xuất bản (Đã xuất bản/Chưa xuất bản)
- **Sửa bài viết**: Tương tự thêm bài viết
- **Xóa bài viết**: Xác nhận trước khi xóa
- **Upload hình ảnh cho TinyMCE**:
  **Route**: `/admin/posts/upload-image`
  - Upload hình ảnh qua AJAX
  - Trả về URL hình ảnh để chèn vào editor

---

## 4. CẤU TRÚC DỮ LIỆU {#cau-truc-du-lieu}

### 4.1. Bảng Users
- `id`: ID người dùng
- `name`: Họ và tên
- `email`: Email (unique)
- `password`: Mật khẩu (hashed)
- `phone`: Số điện thoại
- `address`: Địa chỉ
- `role`: Vai trò (admin/customer)
- `email_verified_at`: Ngày xác thực email
- `remember_token`: Token nhớ đăng nhập
- `created_at`, `updated_at`: Timestamps

### 4.2. Bảng Categories
- `id`: ID danh mục
- `name`: Tên danh mục
- `slug`: URL slug
- `description`: Mô tả
- `status`: Trạng thái (boolean)
- `created_at`, `updated_at`: Timestamps

### 4.3. Bảng Brands
- `id`: ID thương hiệu
- `name`: Tên thương hiệu
- `slug`: URL slug
- `description`: Mô tả
- `status`: Trạng thái (boolean)
- `created_at`, `updated_at`: Timestamps

### 4.4. Bảng Products
- `id`: ID sản phẩm
- `name`: Tên sản phẩm
- `slug`: URL slug
- `description`: Mô tả chi tiết (HTML)
- `short_description`: Mô tả ngắn
- `price`: Giá gốc (decimal)
- `sale_price`: Giá khuyến mãi (decimal, nullable)
- `sku`: SKU sản phẩm (unique)
- `stock_quantity`: Số lượng tồn kho
- `image`: URL hình ảnh (nullable)
- `gallery`: Gallery hình ảnh (JSON, nullable)
- `category_id`: ID danh mục (foreign key)
- `brand_id`: ID thương hiệu (foreign key, nullable)
- `status`: Trạng thái (boolean)
- `featured`: Sản phẩm nổi bật (boolean)
- `created_at`, `updated_at`: Timestamps

### 4.5. Bảng Product Variants
- `id`: ID biến thể
- `product_id`: ID sản phẩm (foreign key)
- `sku`: SKU biến thể (unique)
- `name`: Tên biến thể (nullable)
- `attributes`: Thuộc tính (JSON): {Màu sắc: "...", Kích thước: "..."}
- `price`: Giá biến thể (decimal, nullable)
- `sale_price`: Giá khuyến mãi (decimal, nullable)
- `stock_quantity`: Số lượng tồn kho
- `image`: URL hình ảnh biến thể (nullable)
- `is_default`: Biến thể mặc định (boolean)
- `status`: Trạng thái (boolean)
- `sort_order`: Thứ tự sắp xếp
- `created_at`, `updated_at`: Timestamps

### 4.6. Bảng Carts
- `id`: ID giỏ hàng
- `user_id`: ID người dùng (foreign key)
- `product_id`: ID sản phẩm (foreign key)
- `variant_id`: ID biến thể (foreign key, nullable)
- `quantity`: Số lượng
- `created_at`, `updated_at`: Timestamps

### 4.7. Bảng Orders
- `id`: ID đơn hàng
- `user_id`: ID người dùng (foreign key)
- `order_number`: Mã đơn hàng (unique, format: ORDYYYYMMDDXXXX)
- `total_amount`: Tổng tiền (decimal)
- `subtotal_amount`: Tạm tính (decimal)
- `discount_amount`: Số tiền giảm giá (decimal)
- `coupon_id`: ID mã giảm giá (foreign key, nullable)
- `status`: Trạng thái đơn hàng (pending/processing/shipped/delivered/cancelled)
- `payment_status`: Trạng thái thanh toán (pending/paid/failed)
- `payment_method`: Phương thức thanh toán (VNPAY/COD)
- `shipping_name`: Tên người nhận
- `shipping_phone`: Số điện thoại người nhận
- `shipping_address`: Địa chỉ giao hàng đầy đủ
- `province_id`: ID tỉnh/thành phố
- `district_id`: ID quận/huyện
- `ward_id`: ID phường/xã
- `address_detail`: Địa chỉ chi tiết
- `notes`: Ghi chú
- `vnpay_transaction_id`: ID giao dịch VNPAY (nullable)
- `created_at`, `updated_at`: Timestamps

### 4.8. Bảng Order Items
- `id`: ID item đơn hàng
- `order_id`: ID đơn hàng (foreign key)
- `product_id`: ID sản phẩm (foreign key)
- `variant_id`: ID biến thể (foreign key, nullable)
- `product_name`: Tên sản phẩm (lưu snapshot)
- `product_sku`: SKU sản phẩm (lưu snapshot)
- `quantity`: Số lượng
- `price`: Giá đơn vị (decimal)
- `subtotal`: Thành tiền (decimal)
- `created_at`, `updated_at`: Timestamps

### 4.9. Bảng Payments
- `id`: ID thanh toán
- `order_id`: ID đơn hàng (foreign key)
- `amount`: Số tiền (decimal)
- `payment_method`: Phương thức thanh toán
- `status`: Trạng thái thanh toán
- `transaction_id`: ID giao dịch
- `payment_data`: Dữ liệu thanh toán (JSON)
- `created_at`, `updated_at`: Timestamps

### 4.10. Bảng Coupons
- `id`: ID mã giảm giá
- `code`: Mã giảm giá (unique)
- `name`: Tên mã giảm giá
- `description`: Mô tả
- `type`: Loại (percentage/fixed)
- `value`: Giá trị (decimal)
- `min_purchase_amount`: Giá trị đơn hàng tối thiểu (decimal, nullable)
- `max_discount_amount`: Giá trị giảm tối đa (decimal, nullable)
- `usage_limit`: Giới hạn số lần sử dụng (integer, nullable)
- `used_count`: Số lần đã sử dụng (integer)
- `usage_limit_per_user`: Giới hạn số lần sử dụng mỗi user (integer, nullable)
- `starts_at`: Ngày bắt đầu (datetime, nullable)
- `expires_at`: Ngày hết hạn (datetime, nullable)
- `is_active`: Trạng thái kích hoạt (boolean)
- `created_at`, `updated_at`: Timestamps

### 4.11. Bảng Posts
- `id`: ID bài viết
- `title`: Tiêu đề
- `slug`: URL slug (unique)
- `excerpt`: Mô tả ngắn
- `content`: Nội dung (HTML)
- `featured_image`: Hình ảnh đại diện (nullable)
- `user_id`: ID tác giả (foreign key)
- `is_published`: Đã xuất bản (boolean)
- `published_at`: Ngày xuất bản (datetime, nullable)
- `views`: Số lượt xem (integer)
- `created_at`, `updated_at`: Timestamps

---

## 5. API VÀ TÍCH HỢP {#api-tich-hop}

### 5.1. Address API
**Base URL**: `/api/address`
- **GET `/provinces`**: Lấy danh sách tỉnh/thành phố
- **GET `/districts/{provinceId}`**: Lấy danh sách quận/huyện theo tỉnh
- **GET `/wards/{districtId}`**: Lấy danh sách phường/xã theo quận

### 5.2. Coupon API
**Base URL**: `/api/coupon`
**Middleware**: `auth`
- **POST `/validate`**: Validate mã giảm giá
  - Request: `{code: "MAGIAMGIA"}`
  - Response: 
    ```json
    {
      "success": true,
      "message": "Mã giảm giá hợp lệ",
      "coupon": {...},
      "discount": {
        "amount": 10000,
        "formatted": "10,000₫"
      }
    }
    ```

### 5.3. VNPAY Integration
- **Tạo URL thanh toán**: Tạo URL thanh toán VNPAY với đầy đủ thông tin
- **Xử lý callback**: Nhận và xử lý callback từ VNPAY
- **Xác thực chữ ký**: Xác thực chữ ký để đảm bảo tính toàn vẹn dữ liệu
- **Cập nhật trạng thái**: Tự động cập nhật trạng thái thanh toán

---

## 6. BẢO MẬT VÀ PHÂN QUYỀN {#bao-mat}

### 6.1. Authentication
- **Đăng nhập**: Xác thực bằng email và mật khẩu
- **Remember Me**: Lưu token để tự động đăng nhập
- **Password Hashing**: Sử dụng bcrypt để hash mật khẩu
- **CSRF Protection**: Bảo vệ khỏi tấn công CSRF

### 6.2. Authorization
- **Admin Middleware**: Chỉ admin mới có thể truy cập các route `/admin/*`
- **Auth Middleware**: Yêu cầu đăng nhập cho các route cần xác thực
- **Role-based Access**: Phân quyền dựa trên vai trò (admin/customer)

### 6.3. Validation
- **Form Validation**: Validate tất cả input từ form
- **Unique Constraints**: Đảm bảo tính duy nhất của SKU, email, mã giảm giá
- **Type Casting**: Chuyển đổi kiểu dữ liệu đúng (decimal, integer, boolean)

### 6.4. Data Protection
- **SQL Injection Prevention**: Sử dụng Eloquent ORM và prepared statements
- **XSS Protection**: Escape dữ liệu đầu ra
- **Mass Assignment Protection**: Chỉ cho phép fill các field trong `$fillable`

---

## 7. GIAO DIỆN VÀ UX {#giao-dien}

### 7.1. Design System
- **Framework**: Bootstrap 5
- **Icons**: Bootstrap Icons
- **Custom CSS**: File `public/css/custom.css` với hơn 6000 dòng CSS
- **Responsive**: Tương thích với mọi thiết bị (desktop, tablet, mobile)

### 7.2. UI Components
- **Cards**: Hiển thị sản phẩm, đơn hàng, thống kê
- **Modals**: Form thêm/sửa/xóa
- **Tables**: Danh sách với pagination
- **Forms**: Input, select, textarea với validation
- **Buttons**: Primary, secondary, danger với icons
- **Badges**: Hiển thị trạng thái, số lượng
- **Alerts**: Thông báo success, error, warning

### 7.3. User Experience
- **Loading States**: Spinner khi tải dữ liệu
- **Error Handling**: Hiển thị lỗi rõ ràng
- **Success Messages**: Flash messages khi thao tác thành công
- **Confirmation Dialogs**: Xác nhận trước khi xóa
- **Auto-fill**: Tự động điền thông tin từ user profile
- **Dynamic Dropdowns**: Dropdown địa chỉ phụ thuộc lẫn nhau
- **Image Preview**: Preview hình ảnh trước khi lưu
- **Variant Selection**: Active state khi chọn biến thể sản phẩm

### 7.4. Performance
- **Eager Loading**: Load relationships để tránh N+1 queries
- **Pagination**: Phân trang để giảm tải dữ liệu
- **Image Optimization**: Sử dụng URL ảnh từ CDN hoặc external source
- **Caching**: Có thể cache các query thường dùng

---

## 8. TÍNH NĂNG ĐẶC BIỆT

### 8.1. Product Variants
- Hỗ trợ đầy đủ biến thể sản phẩm (màu sắc, kích thước, dung lượng)
- Mỗi biến thể có giá, số lượng tồn kho riêng
- Hình ảnh riêng cho từng biến thể
- SKU riêng cho từng biến thể
- Biến thể mặc định

### 8.2. Coupon System
- Hỗ trợ 2 loại: Phần trăm (%) và Cố định (VNĐ)
- Giới hạn số lần sử dụng tổng và mỗi user
- Giá trị đơn hàng tối thiểu
- Giá trị giảm tối đa (cho loại phần trăm)
- Thời gian hiệu lực (ngày bắt đầu/kết thúc)
- Tự động validate khi áp dụng mã

### 8.3. Order Number Generation
- Format: `ORDYYYYMMDDXXXX` (ví dụ: ORD202512290001)
- Tự động tăng theo ngày
- Đảm bảo tính duy nhất với retry mechanism
- Xử lý race condition khi nhiều đơn hàng cùng lúc

### 8.4. Address Selection
- Tích hợp API địa chỉ Việt Nam
- Dropdown động: Tỉnh → Quận → Phường
- Tự động điền địa chỉ đầy đủ
- Lưu ID tỉnh/quận/phường để tra cứu sau

### 8.5. Rich Text Editor
- TinyMCE 6 cho mô tả sản phẩm và nội dung bài viết
- Upload hình ảnh qua API
- Hỗ trợ đầy đủ định dạng văn bản
- Giao diện tiếng Việt

---

## 9. SEEDERS VÀ DỮ LIỆU MẪU

### 9.1. User Seeder
- Tạo admin mặc định: `admin@techno1.com` / `password`
- Tạo các user customer mẫu

### 9.2. Category Seeder
- Tạo các danh mục phổ biến (Điện thoại, Laptop, Tablet, ...)
- Tạo thêm danh mục fake

### 9.3. Brand Seeder
- Tạo các thương hiệu phổ biến (Apple, Samsung, Dell, ...)
- Tạo thêm thương hiệu fake

### 9.4. Product Seeder
- Tạo các sản phẩm mẫu với đầy đủ thông tin
- Mỗi sản phẩm có nhiều biến thể (màu sắc, dung lượng)
- Sử dụng URL ảnh fake từ picsum.photos

### 9.5. Coupon Seeder
- Tạo các mã giảm giá mẫu với các loại khác nhau
- Có mã hết hạn, có mã chưa bắt đầu

### 9.6. Post Seeder
- Tạo các bài viết mẫu chuyên nghiệp về công nghệ
- Nội dung đầy đủ với HTML formatting
- Số lượt xem realistic

---

## 10. CẤU HÌNH VÀ THIẾT LẬP

### 10.1. Environment Variables
- `APP_NAME`: Tên ứng dụng
- `APP_ENV`: Môi trường (local/production)
- `APP_DEBUG`: Debug mode
- `APP_URL`: URL ứng dụng
- `APP_TIMEZONE`: Múi giờ (Asia/Ho_Chi_Minh)
- `DB_*`: Thông tin database
- `VNPAY_*`: Thông tin VNPAY
- `MAIL_*`: Thông tin email

### 10.2. Config Files
- `config/app.php`: Cấu hình ứng dụng, timezone, locale
- `config/database.php`: Cấu hình database
- `config/mail.php`: Cấu hình email

---

## 11. HƯỚNG DẪN SỬ DỤNG

### 11.1. Cài đặt
1. Clone repository
2. `composer install`
3. `npm install`
4. Copy `.env.example` thành `.env`
5. `php artisan key:generate`
6. Cấu hình database trong `.env`
7. `php artisan migrate --seed`
8. `php artisan serve`

### 11.2. Đăng nhập Admin
- Email: `admin@techno1.com`
- Password: `password`

### 11.3. Quy trình đặt hàng
1. Khách hàng duyệt sản phẩm
2. Chọn sản phẩm và biến thể (nếu có)
3. Thêm vào giỏ hàng
4. Xem giỏ hàng và cập nhật số lượng
5. Đi đến trang checkout
6. Nhập thông tin giao hàng
7. Áp dụng mã giảm giá (nếu có)
8. Chọn phương thức thanh toán
9. Xác nhận đặt hàng
10. Thanh toán (nếu chọn VNPAY)
11. Nhận xác nhận đơn hàng

### 11.4. Quy trình quản lý đơn hàng (Admin)
1. Xem danh sách đơn hàng
2. Xem chi tiết đơn hàng
3. Cập nhật trạng thái đơn hàng
4. Theo dõi thanh toán

---

## 12. TỔNG KẾT

Hệ thống E-commerce Techno1 là một ứng dụng web hoàn chỉnh với đầy đủ các chức năng cần thiết cho một website bán hàng trực tuyến:

✅ **Quản lý sản phẩm**: CRUD đầy đủ với biến thể sản phẩm
✅ **Quản lý đơn hàng**: Theo dõi và cập nhật trạng thái
✅ **Thanh toán**: Tích hợp VNPAY và COD
✅ **Mã giảm giá**: Hệ thống coupon linh hoạt
✅ **Blog**: Quản lý và hiển thị bài viết
✅ **Giỏ hàng**: Quản lý giỏ hàng với biến thể
✅ **Địa chỉ**: Tích hợp API địa chỉ Việt Nam
✅ **Xác thực**: Đăng ký, đăng nhập, quên mật khẩu
✅ **Giao diện**: Responsive, hiện đại, dễ sử dụng
✅ **Bảo mật**: CSRF protection, SQL injection prevention, XSS protection

Hệ thống đã được kiểm tra và sửa các lỗi, sẵn sàng để triển khai và sử dụng.

---

**Phiên bản**: 1.0.0
**Ngày cập nhật**: 29/12/2025
**Tác giả**: Techno1 Development Team

