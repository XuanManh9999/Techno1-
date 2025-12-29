<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $adminUsers = User::where('role', 'admin')->get();

        if ($adminUsers->isEmpty()) {
            $this->command->warn('Không tìm thấy admin user. Vui lòng chạy UserSeeder trước!');
            return;
        }

        // Tạo các bài viết chuyên nghiệp và đầy đủ
        $posts = [
            [
                'title' => 'Top 10 điện thoại tốt nhất năm 2024: Đánh giá chi tiết từ chuyên gia',
                'excerpt' => 'Tổng hợp danh sách 10 điện thoại thông minh tốt nhất năm 2024 với phân tích chi tiết về hiệu năng, camera, pin và giá cả. Được đánh giá bởi các chuyên gia công nghệ hàng đầu.',
                'content' => '<h2>Giới thiệu</h2>
                <p>Năm 2024 đã chứng kiến sự phát triển vượt bậc của công nghệ điện thoại thông minh với nhiều cải tiến đáng kể về hiệu năng, camera và trải nghiệm người dùng. Dưới đây là danh sách top 10 điện thoại tốt nhất mà bạn không nên bỏ lỡ.</p>
                
                <h2>1. iPhone 15 Pro Max - Vua của điện thoại cao cấp</h2>
                <p>iPhone 15 Pro Max là flagship mới nhất của Apple, được trang bị chip A17 Pro mạnh mẽ nhất từ trước đến nay. Với camera 48MP chính và hệ thống zoom quang học 5x, đây là chiếc điện thoại hoàn hảo cho những người đam mê nhiếp ảnh.</p>
                <ul>
                    <li><strong>Hiệu năng:</strong> Chip A17 Pro với 6 nhân CPU và GPU 6 nhân</li>
                    <li><strong>Camera:</strong> Hệ thống 3 camera với zoom quang học 5x</li>
                    <li><strong>Pin:</strong> Thời lượng sử dụng lên đến 29 giờ video</li>
                    <li><strong>Màn hình:</strong> Super Retina XDR 6.7 inch với ProMotion 120Hz</li>
                </ul>
                
                <h2>2. Samsung Galaxy S24 Ultra - Sức mạnh và sự linh hoạt</h2>
                <p>Samsung Galaxy S24 Ultra sở hữu màn hình Dynamic AMOLED 2X tuyệt đẹp với độ sáng cao và màu sắc chân thực. Bút S Pen tích hợp mang đến trải nghiệm ghi chú và vẽ độc đáo.</p>
                <ul>
                    <li><strong>Hiệu năng:</strong> Snapdragon 8 Gen 3 cho Galaxy</li>
                    <li><strong>Camera:</strong> Hệ thống 4 camera với zoom quang học 10x</li>
                    <li><strong>Màn hình:</strong> 6.8 inch Dynamic AMOLED 2X với độ sáng 2600 nits</li>
                    <li><strong>Đặc biệt:</strong> S Pen tích hợp, hỗ trợ AI mạnh mẽ</li>
                </ul>
                
                <h2>3. Xiaomi 14 Pro - Hiệu năng giá tốt</h2>
                <p>Xiaomi 14 Pro mang đến hiệu năng flagship với giá cả hợp lý, phù hợp với người dùng Việt Nam. Với camera Leica và chip Snapdragon 8 Gen 3, đây là lựa chọn tuyệt vời trong phân khúc giá trung bình.</p>
                
                <h2>4. Oppo Find X7 Ultra - Camera đỉnh cao</h2>
                <p>Oppo Find X7 Ultra nổi bật với hệ thống camera 4 camera Hasselblad, mang đến chất lượng ảnh chuyên nghiệp.</p>
                
                <h2>5. Vivo X100 Pro - Sáng tạo không giới hạn</h2>
                <p>Vivo X100 Pro với chip MediaTek Dimensity 9300 và camera Zeiss, là lựa chọn tuyệt vời cho người dùng yêu thích nhiếp ảnh.</p>
                
                <h2>Kết luận</h2>
                <p>Mỗi chiếc điện thoại trong danh sách này đều có những điểm mạnh riêng. Tùy thuộc vào nhu cầu và ngân sách của bạn, hãy chọn cho mình chiếc điện thoại phù hợp nhất.</p>',
                'featured_image' => 'https://picsum.photos/1200/600?random=1',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5),
                'views' => 12500,
            ],
            [
                'title' => 'Hướng dẫn chọn laptop phù hợp: Từ công việc văn phòng đến gaming',
                'excerpt' => 'Bài viết hướng dẫn chi tiết cách chọn laptop phù hợp với nhu cầu công việc và giải trí của bạn. Bao gồm các tiêu chí đánh giá và gợi ý sản phẩm cụ thể.',
                'content' => '<h2>Giới thiệu</h2>
                <p>Việc chọn laptop phù hợp không chỉ phụ thuộc vào giá cả mà còn phụ thuộc vào nhu cầu sử dụng của bạn. Bài viết này sẽ giúp bạn có cái nhìn tổng quan và đưa ra quyết định đúng đắn.</p>
                
                <h2>1. Laptop cho công việc văn phòng</h2>
                <p>Nếu bạn chỉ cần laptop để làm việc văn phòng, xử lý tài liệu Word, Excel, PowerPoint và duyệt web, thì một chiếc laptop có CPU Intel Core i5 hoặc AMD Ryzen 5 là đủ.</p>
                <h3>Yêu cầu cấu hình:</h3>
                <ul>
                    <li>CPU: Intel Core i5 hoặc AMD Ryzen 5</li>
                    <li>RAM: 8GB trở lên</li>
                    <li>Ổ cứng: SSD 256GB trở lên</li>
                    <li>Màn hình: 14-15.6 inch Full HD</li>
                </ul>
                <h3>Gợi ý sản phẩm:</h3>
                <ul>
                    <li>Dell XPS 13</li>
                    <li>HP Pavilion 15</li>
                    <li>Lenovo ThinkPad E14</li>
                </ul>
                
                <h2>2. Laptop cho thiết kế đồ họa và video</h2>
                <p>Đối với công việc thiết kế đồ họa, chỉnh sửa video, bạn cần laptop có card đồ họa rời và RAM từ 16GB trở lên để đảm bảo hiệu năng xử lý mượt mà.</p>
                <h3>Yêu cầu cấu hình:</h3>
                <ul>
                    <li>CPU: Intel Core i7 hoặc AMD Ryzen 7 trở lên</li>
                    <li>RAM: 16GB trở lên</li>
                    <li>Card đồ họa: NVIDIA RTX 3050 trở lên</li>
                    <li>Ổ cứng: SSD 512GB trở lên</li>
                    <li>Màn hình: 15.6 inch trở lên, độ phân giải cao</li>
                </ul>
                <h3>Gợi ý sản phẩm:</h3>
                <ul>
                    <li>MacBook Pro 14/16 inch M3</li>
                    <li>Dell XPS 15</li>
                    <li>Asus ProArt StudioBook</li>
                </ul>
                
                <h2>3. Laptop cho gaming</h2>
                <p>Gaming laptop cần có card đồ họa mạnh, CPU tốt và màn hình có tần số quét cao (144Hz trở lên) để mang lại trải nghiệm chơi game mượt mà.</p>
                <h3>Yêu cầu cấu hình:</h3>
                <ul>
                    <li>CPU: Intel Core i7 hoặc AMD Ryzen 7 trở lên</li>
                    <li>RAM: 16GB trở lên</li>
                    <li>Card đồ họa: NVIDIA RTX 4060 trở lên</li>
                    <li>Màn hình: 144Hz trở lên, độ phân giải Full HD hoặc QHD</li>
                    <li>Ổ cứng: SSD 512GB trở lên</li>
                </ul>
                <h3>Gợi ý sản phẩm:</h3>
                <ul>
                    <li>Asus ROG Strix G16</li>
                    <li>MSI Katana 15</li>
                    <li>Lenovo Legion 5 Pro</li>
                </ul>
                
                <h2>4. Laptop cho sinh viên</h2>
                <p>Laptop cho sinh viên cần cân bằng giữa hiệu năng và giá cả, đủ mạnh để học tập và giải trí nhẹ.</p>
                <h3>Yêu cầu cấu hình:</h3>
                <ul>
                    <li>CPU: Intel Core i5 hoặc AMD Ryzen 5</li>
                    <li>RAM: 8GB trở lên</li>
                    <li>Ổ cứng: SSD 256GB trở lên</li>
                    <li>Pin: Thời lượng pin tốt (8 giờ trở lên)</li>
                </ul>
                
                <h2>Kết luận</h2>
                <p>Việc chọn laptop phù hợp sẽ giúp bạn làm việc hiệu quả hơn và tiết kiệm chi phí. Hãy xác định rõ nhu cầu của mình trước khi quyết định mua.</p>',
                'featured_image' => 'https://picsum.photos/1200/600?random=2',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(3),
                'views' => 8900,
            ],
            [
                'title' => 'Xu hướng công nghệ năm 2024: AI, VR/AR và IoT đang thay đổi thế giới',
                'excerpt' => 'Khám phá những xu hướng công nghệ nổi bật nhất trong năm 2024 và tác động của chúng đến cuộc sống hàng ngày. Từ trí tuệ nhân tạo đến thực tế ảo, công nghệ đang định hình lại tương lai.',
                'content' => '<h2>Giới thiệu</h2>
                <p>Năm 2024 đánh dấu sự phát triển vượt bậc của nhiều công nghệ mới, từ trí tuệ nhân tạo đến thực tế ảo. Những xu hướng này không chỉ thay đổi cách chúng ta làm việc mà còn định hình lại cuộc sống hàng ngày.</p>
                
                <h2>1. Trí tuệ nhân tạo (AI) - Từ chatbot đến công cụ sáng tạo</h2>
                <p>AI đang ngày càng được tích hợp sâu vào các sản phẩm công nghệ, từ điện thoại đến laptop. Các ứng dụng AI như ChatGPT, Midjourney đang thay đổi cách chúng ta làm việc và sáng tạo.</p>
                <h3>Ứng dụng thực tế:</h3>
                <ul>
                    <li><strong>Điện thoại thông minh:</strong> AI được tích hợp vào camera để cải thiện chất lượng ảnh</li>
                    <li><strong>Laptop:</strong> AI giúp tối ưu hiệu năng và tiết kiệm pin</li>
                    <li><strong>Ứng dụng:</strong> Trợ lý ảo thông minh, dịch thuật tự động</li>
                </ul>
                
                <h2>2. Thực tế ảo (VR) và Thực tế tăng cường (AR)</h2>
                <p>VR và AR đang mở ra những khả năng mới trong giải trí và công việc. Từ gaming đến đào tạo, công nghệ này đang được ứng dụng rộng rãi.</p>
                <h3>Thiết bị nổi bật:</h3>
                <ul>
                    <li>Meta Quest 3 - VR headset phổ biến nhất</li>
                    <li>Apple Vision Pro - AR headset cao cấp</li>
                    <li>Microsoft HoloLens - Ứng dụng trong công nghiệp</li>
                </ul>
                
                <h2>3. Internet vạn vật (IoT) - Nhà thông minh</h2>
                <p>Ngày càng nhiều thiết bị được kết nối internet, tạo nên một hệ sinh thái thông minh. Từ đèn, điều hòa đến tủ lạnh, mọi thứ đều có thể được điều khiển từ xa.</p>
                <h3>Ứng dụng phổ biến:</h3>
                <ul>
                    <li>Nhà thông minh với Google Home, Amazon Alexa</li>
                    <li>Thiết bị đeo thông minh theo dõi sức khỏe</li>
                    <li>Xe tự lái và thành phố thông minh</li>
                </ul>
                
                <h2>4. 5G và kết nối siêu tốc</h2>
                <p>Mạng 5G đang được triển khai rộng rãi, mang đến tốc độ internet nhanh hơn và độ trễ thấp hơn. Điều này mở ra nhiều khả năng mới cho các ứng dụng thời gian thực.</p>
                
                <h2>5. Bảo mật và quyền riêng tư</h2>
                <p>Với sự phát triển của công nghệ, vấn đề bảo mật và quyền riêng tư ngày càng quan trọng. Các công ty đang đầu tư nhiều hơn vào bảo mật dữ liệu.</p>
                
                <h2>Kết luận</h2>
                <p>Những xu hướng công nghệ này đang định hình lại cách chúng ta sống và làm việc. Việc nắm bắt và ứng dụng chúng sẽ giúp chúng ta tận dụng tối đa tiềm năng của công nghệ.</p>',
                'featured_image' => 'https://picsum.photos/1200/600?random=3',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(1),
                'views' => 15200,
            ],
            [
                'title' => 'Bảo vệ dữ liệu cá nhân trên điện thoại: Hướng dẫn chi tiết từ A-Z',
                'excerpt' => 'Những cách đơn giản nhưng hiệu quả để bảo vệ dữ liệu cá nhân và quyền riêng tư trên điện thoại thông minh. Bao gồm các mẹo bảo mật và công cụ hỗ trợ.',
                'content' => '<h2>Giới thiệu</h2>
                <p>Bảo vệ dữ liệu cá nhân là vấn đề quan trọng trong thời đại số. Điện thoại thông minh chứa rất nhiều thông tin nhạy cảm, từ ảnh cá nhân đến thông tin ngân hàng. Bài viết này sẽ hướng dẫn bạn cách bảo vệ dữ liệu một cách hiệu quả.</p>
                
                <h2>1. Sử dụng khóa màn hình mạnh</h2>
                <p>Luôn đặt mật khẩu, mã PIN hoặc khóa sinh trắc học cho điện thoại của bạn. Đây là lớp bảo vệ đầu tiên và quan trọng nhất.</p>
                <h3>Các phương thức khóa:</h3>
                <ul>
                    <li><strong>Mật khẩu:</strong> Sử dụng mật khẩu dài ít nhất 6 ký tự</li>
                    <li><strong>Mã PIN:</strong> Tối thiểu 6 số</li>
                    <li><strong>Vân tay:</strong> Nhanh chóng và tiện lợi</li>
                    <li><strong>Face ID/Face Unlock:</strong> Công nghệ nhận diện khuôn mặt</li>
                </ul>
                
                <h2>2. Cập nhật hệ điều hành thường xuyên</h2>
                <p>Thường xuyên cập nhật hệ điều hành để nhận các bản vá bảo mật mới nhất. Các bản cập nhật thường chứa các bản sửa lỗi bảo mật quan trọng.</p>
                
                <h2>3. Cẩn thận với ứng dụng</h2>
                <p>Chỉ tải ứng dụng từ các cửa hàng chính thức như App Store (iOS) hoặc Google Play (Android). Kiểm tra quyền truy cập của ứng dụng trước khi cài đặt.</p>
                <h3>Kiểm tra quyền truy cập:</h3>
                <ul>
                    <li>Ứng dụng có cần truy cập vị trí không?</li>
                    <li>Có cần truy cập danh bạ không?</li>
                    <li>Có cần truy cập camera/microphone không?</li>
                </ul>
                
                <h2>4. Sử dụng VPN khi cần</h2>
                <p>Khi sử dụng WiFi công cộng, hãy sử dụng VPN để mã hóa kết nối và bảo vệ dữ liệu của bạn.</p>
                
                <h2>5. Sao lưu dữ liệu định kỳ</h2>
                <p>Thường xuyên sao lưu dữ liệu lên đám mây hoặc máy tính để tránh mất dữ liệu khi điện thoại bị hỏng hoặc mất.</p>
                
                <h2>6. Xóa dữ liệu khi bán/cho điện thoại</h2>
                <p>Trước khi bán hoặc cho điện thoại, hãy xóa tất cả dữ liệu và khôi phục cài đặt gốc để đảm bảo không ai có thể truy cập thông tin của bạn.</p>
                
                <h2>Kết luận</h2>
                <p>Bảo vệ dữ liệu cá nhân là trách nhiệm của mỗi người dùng. Hãy áp dụng các biện pháp trên để đảm bảo an toàn cho thông tin của bạn.</p>',
                'featured_image' => 'https://picsum.photos/1200/600?random=4',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(7),
                'views' => 6800,
            ],
            [
                'title' => 'Đánh giá tai nghe không dây tốt nhất 2024: So sánh chi tiết AirPods, Sony và Samsung',
                'excerpt' => 'So sánh và đánh giá chi tiết các mẫu tai nghe không dây tốt nhất trên thị trường hiện tại. Bao gồm AirPods Pro 2, Sony WH-1000XM5 và Samsung Galaxy Buds2 Pro.',
                'content' => '<h2>Giới thiệu</h2>
                <p>Tai nghe không dây đã trở thành phụ kiện không thể thiếu cho nhiều người. Với công nghệ ngày càng phát triển, chất lượng âm thanh và tính năng của tai nghe không dây đã được cải thiện đáng kể.</p>
                
                <h2>1. AirPods Pro 2 - Lựa chọn hàng đầu cho người dùng Apple</h2>
                <p>AirPods Pro 2 là phiên bản nâng cấp của AirPods Pro với nhiều cải tiến về chất lượng âm thanh và chống ồn.</p>
                <h3>Điểm mạnh:</h3>
                <ul>
                    <li>Công nghệ chống ồn chủ động (ANC) cải tiến</li>
                    <li>Chất lượng âm thanh Spatial Audio tuyệt vời</li>
                    <li>Tích hợp sâu với hệ sinh thái Apple</li>
                    <li>Pin dùng được đến 6 giờ (30 giờ với hộp sạc)</li>
                </ul>
                <h3>Điểm yếu:</h3>
                <ul>
                    <li>Giá cao</li>
                    <li>Chỉ tối ưu cho người dùng Apple</li>
                </ul>
                
                <h2>2. Sony WH-1000XM5 - Vua chống ồn</h2>
                <p>Sony WH-1000XM5 có khả năng chống ồn tốt nhất trong phân khúc và pin dùng được đến 30 giờ.</p>
                <h3>Điểm mạnh:</h3>
                <ul>
                    <li>Chống ồn tốt nhất thị trường</li>
                    <li>Chất lượng âm thanh Hi-Res Audio</li>
                    <li>Pin cực kỳ lâu (30 giờ)</li>
                    <li>Thiết kế thoải mái</li>
                </ul>
                <h3>Điểm yếu:</h3>
                <ul>
                    <li>Kích thước lớn, không gọn nhẹ</li>
                    <li>Giá cao</li>
                </ul>
                
                <h2>3. Samsung Galaxy Buds2 Pro - Giá trị tốt</h2>
                <p>Galaxy Buds2 Pro mang đến chất lượng âm thanh cao và giá cả hợp lý, phù hợp với người dùng Android.</p>
                <h3>Điểm mạnh:</h3>
                <ul>
                    <li>Giá cả hợp lý</li>
                    <li>Chất lượng âm thanh 360 Audio</li>
                    <li>Thiết kế nhỏ gọn</li>
                    <li>Tích hợp tốt với Samsung Galaxy</li>
                </ul>
                
                <h2>4. So sánh tổng quan</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tính năng</th>
                            <th>AirPods Pro 2</th>
                            <th>Sony WH-1000XM5</th>
                            <th>Galaxy Buds2 Pro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Chống ồn</td>
                            <td>Rất tốt</td>
                            <td>Tốt nhất</td>
                            <td>Tốt</td>
                        </tr>
                        <tr>
                            <td>Chất lượng âm thanh</td>
                            <td>Xuất sắc</td>
                            <td>Xuất sắc</td>
                            <td>Rất tốt</td>
                        </tr>
                        <tr>
                            <td>Pin</td>
                            <td>6 giờ</td>
                            <td>30 giờ</td>
                            <td>8 giờ</td>
                        </tr>
                        <tr>
                            <td>Giá</td>
                            <td>Cao</td>
                            <td>Cao</td>
                            <td>Trung bình</td>
                        </tr>
                    </tbody>
                </table>
                
                <h2>Kết luận</h2>
                <p>Mỗi mẫu tai nghe đều có những điểm mạnh riêng. Nếu bạn là người dùng Apple, AirPods Pro 2 là lựa chọn tốt nhất. Nếu bạn cần chống ồn tốt nhất, hãy chọn Sony WH-1000XM5. Còn nếu bạn muốn giá trị tốt, Galaxy Buds2 Pro là lựa chọn phù hợp.</p>',
                'featured_image' => 'https://picsum.photos/1200/600?random=5',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(2),
                'views' => 11200,
            ],
            [
                'title' => 'MacBook Pro M3 vs Windows Laptop: Nên chọn gì?',
                'excerpt' => 'So sánh chi tiết giữa MacBook Pro M3 và các laptop Windows hàng đầu. Phân tích ưu nhược điểm của từng nền tảng để giúp bạn đưa ra quyết định phù hợp.',
                'content' => '<h2>Giới thiệu</h2>
                <p>Việc chọn giữa MacBook và Windows laptop là một quyết định quan trọng. Mỗi nền tảng đều có những ưu điểm riêng. Bài viết này sẽ giúp bạn hiểu rõ hơn về sự khác biệt.</p>
                
                <h2>MacBook Pro M3 - Ưu điểm</h2>
                <ul>
                    <li>Hiệu năng mạnh mẽ với chip M3</li>
                    <li>Pin cực kỳ lâu (18-22 giờ)</li>
                    <li>Màn hình Retina tuyệt đẹp</li>
                    <li>Hệ sinh thái Apple tích hợp tốt</li>
                    <li>Thiết kế cao cấp, bền bỉ</li>
                </ul>
                
                <h2>Windows Laptop - Ưu điểm</h2>
                <ul>
                    <li>Đa dạng lựa chọn và giá cả</li>
                    <li>Tương thích phần mềm rộng rãi</li>
                    <li>Dễ tùy biến và nâng cấp</li>
                    <li>Gaming tốt hơn với card đồ họa rời</li>
                    <li>Hỗ trợ nhiều cổng kết nối</li>
                </ul>
                
                <h2>Kết luận</h2>
                <p>Tùy thuộc vào nhu cầu và ngân sách của bạn, cả hai đều là lựa chọn tốt.</p>',
                'featured_image' => 'https://picsum.photos/1200/600?random=6',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(4),
                'views' => 9500,
            ],
            [
                'title' => 'Hướng dẫn sử dụng iPad Pro hiệu quả cho công việc và học tập',
                'excerpt' => 'Khám phá các tính năng và ứng dụng hữu ích trên iPad Pro để tối ưu hóa công việc và học tập. Từ ghi chú đến chỉnh sửa video.',
                'content' => '<h2>Giới thiệu</h2>
                <p>iPad Pro không chỉ là một thiết bị giải trí mà còn là công cụ mạnh mẽ cho công việc và học tập. Bài viết này sẽ hướng dẫn bạn cách sử dụng hiệu quả.</p>
                
                <h2>1. Ghi chú với Apple Pencil</h2>
                <p>Apple Pencil biến iPad Pro thành một cuốn sổ ghi chú kỹ thuật số hoàn hảo. Ứng dụng Notes và Notability là những công cụ tuyệt vời.</p>
                
                <h2>2. Chỉnh sửa video và ảnh</h2>
                <p>Với chip M2 mạnh mẽ, iPad Pro có thể xử lý chỉnh sửa video 4K một cách mượt mà. Ứng dụng như LumaFusion và Adobe Lightroom là lựa chọn tốt.</p>
                
                <h2>3. Làm việc đa nhiệm</h2>
                <p>iPadOS hỗ trợ Split View và Slide Over để bạn có thể làm việc với nhiều ứng dụng cùng lúc.</p>
                
                <h2>Kết luận</h2>
                <p>iPad Pro là một công cụ đa năng mạnh mẽ. Hãy khám phá các tính năng để tận dụng tối đa.</p>',
                'featured_image' => 'https://picsum.photos/1200/600?random=7',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(6),
                'views' => 7200,
            ],
            [
                'title' => 'Công nghệ sạc nhanh: Từ 15W đến 200W, bạn cần gì?',
                'excerpt' => 'Tìm hiểu về công nghệ sạc nhanh trên các thiết bị di động. So sánh các tiêu chuẩn sạc và đưa ra lời khuyên về việc chọn sạc phù hợp.',
                'content' => '<h2>Giới thiệu</h2>
                <p>Công nghệ sạc nhanh đã phát triển rất nhiều trong những năm gần đây. Từ sạc 15W đến 200W, có rất nhiều lựa chọn.</p>
                
                <h2>1. Sạc nhanh là gì?</h2>
                <p>Sạc nhanh cho phép bạn sạc điện thoại nhanh hơn nhiều so với sạc thông thường. Công nghệ này sử dụng điện áp và dòng điện cao hơn.</p>
                
                <h2>2. Các tiêu chuẩn sạc nhanh</h2>
                <ul>
                    <li><strong>USB Power Delivery:</strong> Tiêu chuẩn phổ biến nhất</li>
                    <li><strong>Qualcomm Quick Charge:</strong> Cho điện thoại Android</li>
                    <li><strong>Apple Fast Charging:</strong> Cho iPhone</li>
                    <li><strong>OnePlus Warp Charge:</strong> Công nghệ độc quyền</li>
                </ul>
                
                <h2>3. Bạn cần công suất bao nhiêu?</h2>
                <p>Hầu hết điện thoại hiện đại hỗ trợ sạc từ 18W đến 30W. Sạc cao hơn không nhất thiết sẽ nhanh hơn vì điện thoại sẽ tự điều chỉnh.</p>
                
                <h2>Kết luận</h2>
                <p>Chọn sạc phù hợp với thiết bị của bạn và đảm bảo chất lượng để tránh hỏng pin.</p>',
                'featured_image' => 'https://picsum.photos/1200/600?random=8',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(8),
                'views' => 5400,
            ],
        ];

        foreach ($posts as $postData) {
            $postData['user_id'] = $adminUsers->random()->id;
            $postData['slug'] = Str::slug($postData['title']);
            Post::create($postData);
        }

        // Tạo thêm 20 bài viết ngẫu nhiên với nội dung phong phú hơn
        $topics = [
            'Đánh giá sản phẩm',
            'Hướng dẫn sử dụng',
            'So sánh công nghệ',
            'Mẹo và thủ thuật',
            'Tin tức công nghệ',
            'Review chi tiết',
            'Hướng dẫn mua hàng',
            'Bảo mật và an toàn',
        ];

        for ($i = 0; $i < 20; $i++) {
            $topic = $faker->randomElement($topics);
            $title = $topic . ': ' . $faker->sentence(rand(4, 8));
            $publishedAt = Carbon::now()->subDays($faker->numberBetween(0, 30));
            
            // Tạo nội dung phong phú hơn
            $paragraphs = [];
            $paragraphs[] = '<h2>Giới thiệu</h2><p>' . $faker->paragraph(3) . '</p>';
            
            for ($j = 0; $j < rand(3, 6); $j++) {
                $paragraphs[] = '<h2>' . ($j + 1) . '. ' . $faker->sentence(rand(4, 8)) . '</h2>';
                $paragraphs[] = '<p>' . $faker->paragraph(rand(2, 4)) . '</p>';
                if ($faker->boolean(50)) {
                    $paragraphs[] = '<ul><li>' . implode('</li><li>', $faker->sentences(rand(2, 4))) . '</li></ul>';
                }
            }
            
            $paragraphs[] = '<h2>Kết luận</h2><p>' . $faker->paragraph(2) . '</p>';
            
            Post::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . $i,
                'excerpt' => $faker->sentence(rand(12, 20)),
                'content' => implode('', $paragraphs),
                'featured_image' => 'https://picsum.photos/1200/600?random=' . rand(10, 1000),
                'user_id' => $adminUsers->random()->id,
                'is_published' => $faker->boolean(85), // 85% published
                'published_at' => $faker->boolean(85) ? $publishedAt : null,
                'views' => $faker->numberBetween(100, 8000),
            ]);
        }
    }
}
