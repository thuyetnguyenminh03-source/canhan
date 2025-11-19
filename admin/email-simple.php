<?php
// Email helper đơn giản không cần Composer
function sendEmailSimple($to, $subject, $body, $headers = '') {
    global $config;
    
    if (!isset($config['email']) || !$config['email']['enabled']) {
        return ['success' => false, 'error' => 'Email chưa được cấu hình'];
    }
    
    try {
        // Tạo headers nếu chưa có
        if (empty($headers)) {
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: " . $config['email']['from_name'] . " <" . $config['email']['from_email'] . ">\r\n";
            $headers .= "Reply-To: " . $config['email']['from_email'] . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
        }
        
        // Gửi email bằng PHP mail function
        $result = mail($to, $subject, $body, $headers);
        
        if ($result) {
            return ['success' => true, 'message' => 'Email đã được gửi thành công'];
        } else {
            return ['success' => false, 'error' => 'Không thể gửi email. Vui lòng kiểm tra cấu hình email server.'];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Lỗi gửi email: ' . $e->getMessage()];
    }
}

function sendPasswordResetEmail($to, $resetLink) {
    global $config;
    
    $subject = '=?UTF-8?B?' . base64_encode('Đặt lại mật khẩu Admin - Portfolio') . '?=';
    
    $body = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #f8f9fa;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; color: white;">
            <h1 style="margin: 0; font-size: 28px;">🔐 Đặt lại mật khẩu Admin</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Portfolio Management System</p>
        </div>
        
        <div style="padding: 40px; background: white; margin: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <h2 style="color: #333; margin-bottom: 20px;">Xin chào!</h2>
            
            <p style="color: #555; font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
                Bạn đã yêu cầu đặt lại mật khẩu admin cho portfolio của bạn. Vui lòng click vào nút bên dưới để tiếp tục:
            </p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . $resetLink . '" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 30px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s ease;">
                    🔑 Đặt lại mật khẩu ngay
                </a>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <p style="color: #666; font-size: 14px; margin: 0; text-align: center;">
                    <strong>Hoặc sao chép link này:</strong><br>
                    <code style="background: white; padding: 8px 12px; border-radius: 5px; font-family: monospace; word-break: break-all; display: inline-block; margin-top: 10px; border: 1px solid #ddd;">' . $resetLink . '</code>
                </p>
            </div>
            
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <p style="color: #856404; margin: 0; font-size: 14px;">
                    <strong>⚠️ Lưu ý quan trọng:</strong><br>
                    • Link này chỉ có hiệu lực trong <strong>1 giờ</strong><br>
                    • Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này<br>
                    • Vì lý do bảo mật, đừng chia sẻ link này với người khác
                </p>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: #666; font-size: 14px; margin: 0;">
                    Trân trọng,<br>
                    <strong style="color: #333;">Portfolio Admin System</strong>
                </p>
            </div>
        </div>
        
        <div style="background: #333; color: white; padding: 25px; text-align: center; font-size: 12px; border-radius: 0 0 10px 10px;">
            <p style="margin: 0 0 10px 0;">© 2024 Portfolio Admin. All rights reserved.</p>
            <p style="margin: 0; opacity: 0.8;">Nếu bạn có thắc mắc, vui lòng liên hệ quản trị viên.</p>
        </div>
    </div>';
    
    return sendEmailSimple($to, $subject, $body);
}

function sendNewPasswordEmail($to, $newPassword) {
    global $config;
    
    $subject = '=?UTF-8?B?' . base64_encode('Mật khẩu Admin mới - Portfolio') . '?=';
    
    $body = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #f8f9fa;">
        <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; text-align: center; color: white;">
            <h1 style="margin: 0; font-size: 28px;">🔑 Mật khẩu mới</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Portfolio Management System</p>
        </div>
        
        <div style="padding: 40px; background: white; margin: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <h2 style="color: #333; margin-bottom: 20px;">Xin chào!</h2>
            
            <p style="color: #555; font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
                Mật khẩu admin mới của bạn đã được tạo thành công. Dưới đây là mật khẩu mới:
            </p>
            
            <div style="text-align: center; margin: 30px 0;">
                <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; border: 2px solid #28a745; display: inline-block;">
                    <p style="color: #333; font-weight: bold; margin: 0 0 10px 0;">Mật khẩu mới của bạn:</p>
                    <div style="background: white; padding: 15px 25px; border-radius: 8px; font-family: monospace; font-size: 20px; letter-spacing: 2px; color: #dc3545; font-weight: bold; border: 1px solid #ddd; margin: 10px 0;">
                        ' . $newPassword . '
                    </div>
                </div>
            </div>
            
            <div style="background: #d1ecf1; border: 1px solid #bee5eb; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <p style="color: #0c5460; margin: 0; font-size: 14px;">
                    <strong>💡 Gợi ý bảo mật:</strong><br>
                    • Đăng nhập ngay và đổi mật khẩu này thành mật khẩu của riêng bạn<br>
                    • Sử dụng mật khẩu mạnh và duy nhất cho mỗi tài khoản<br>
                    • Không chia sẻ mật khẩu với người khác<br>
                    • Lưu mật khẩu ở nơi an toàn
                </p>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: #666; font-size: 14px; margin: 0;">
                    Trân trọng,<br>
                    <strong style="color: #333;">Portfolio Admin System</strong>
                </p>
            </div>
        </div>
        
        <div style="background: #333; color: white; padding: 25px; text-align: center; font-size: 12px; border-radius: 0 0 10px 10px;">
            <p style="margin: 0 0 10px 0;">© 2024 Portfolio Admin. All rights reserved.</p>
            <p style="margin: 0; opacity: 0.8;">Nếu bạn có thắc mắc, vui lòng liên hệ quản trị viên.</p>
        </div>
    </div>';
    
    return sendEmailSimple($to, $subject, $body);
}