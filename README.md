# Techno1 E-commerce Website

Website bán trang thiết bị điện tử Techno1 được xây dựng bằng PHP Laravel Framework.

## Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM

## Cài đặt

1. Clone repository
2. Cài đặt dependencies:
```bash
composer install
npm install
```

3. Copy file .env.example thành .env:
```bash
cp .env.example .env
```

4. Tạo APP_KEY:
```bash
php artisan key:generate
```

5. Cấu hình database trong file .env

6. Chạy migrations và seeders:
```bash
php artisan migrate --seed
```

7. Chạy server:
```bash
php artisan serve
```

## Cấu hình

### Database
Cập nhật thông tin database trong file .env:
- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=techno1_db
- DB_USERNAME=root
- DB_PASSWORD=

### VNPAY
Cập nhật thông tin VNPAY trong file .env:
- VNPAY_TMN_CODE=your_tmn_code
- VNPAY_HASH_SECRET=your_hash_secret
- VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
- VNPAY_RETURN_URL=http://localhost/payment/return

### Email (Cho tính năng quên mật khẩu)
Cấu hình email trong file .env:
- MAIL_MAILER=smtp
- MAIL_HOST=smtp.gmail.com (hoặc smtp server khác)
- MAIL_PORT=587
- MAIL_USERNAME=your_email@gmail.com
- MAIL_PASSWORD=your_app_password
- MAIL_ENCRYPTION=tls
- MAIL_FROM_ADDRESS=your_email@gmail.com
- MAIL_FROM_NAME="Techno1"

**Lưu ý:** Nếu dùng Gmail, cần tạo App Password thay vì mật khẩu thường.

## Tính năng

### Khách hàng
- Xem danh sách sản phẩm
- Tìm kiếm và lọc sản phẩm (theo danh mục, thương hiệu, giá)
- Xem chi tiết sản phẩm
- Thêm vào giỏ hàng
- Quản lý giỏ hàng (cập nhật số lượng, xóa sản phẩm)
- Đặt hàng với thông tin giao hàng
- Thanh toán qua VNPAY
- Xem lịch sử đơn hàng
- Quên mật khẩu qua email

### Quản trị viên
- Dashboard với thống kê tổng quan
- Quản lý sản phẩm (CRUD)
- Quản lý đơn hàng
- Cập nhật trạng thái đơn hàng
- Xem thông tin thanh toán

## Thông tin đăng nhập mặc định

Sau khi chạy seeder:
- **Admin:** 
  - Email: `admin@techno1.com`
  - Password: `password`
- **Customer:** 
  - Email: `customer@example.com`
  - Password: `password`

## Giao diện

- Responsive design, tương thích với mọi thiết bị (desktop, tablet, mobile)
- Giao diện hiện đại với Bootstrap 5
- CSS custom với animations và transitions
- Icons từ Bootstrap Icons

## Cấu trúc dự án

```
App/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── Auth/
│   │   │   └── ...
│   │   └── Middleware/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── css/
│       └── custom.css
├── resources/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── layouts/
│       └── ...
└── routes/
    └── web.php
```

## License

MIT
