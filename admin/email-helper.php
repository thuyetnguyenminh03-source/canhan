<?php
// Email helper để gửi email với PHPMailer
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $body, $altBody = '') {
    global $config;
    
    if (!isset($config['email']) || !$config['email']['enabled']) {
        return ['success' => false, 'error' => 'Email chưa được cấu hình'];
    }
    
    $mail = new PHPMailer(true);
    
    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host = $config['email']['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['email']['smtp_username'];
        $mail->Password = $config['email']['smtp_password'];
        $mail->SMTPSecure = $config['email']['smtp_encryption'];
        $mail->Port = $config['email']['smtp_port'];
        
        // Người gửi
        $mail->setFrom($config['email']['from_email'], $config['email']['from_name']);
        
        // Người nhận
        $mail->addAddress($to);
        
        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $altBody ?: strip_tags($body);
        
        $mail->send();
        
        return ['success' => true, 'message' => 'Email đã được gửi thành công'];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Không thể gửi email: ' . $mail->ErrorInfo];
    }
}

function sendPasswordResetEmail($to, $resetLink) {
    $subject = 'Đặt lại mật khẩu Admin - Portfolio';
    
    $body = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; text-align: center; color: white;">
            <h1 style="margin: 0;">🔐 Đặt lại mật khẩu Admin</h1>
        </div>
        
        <div style="padding: 30px; background: #f8f9fa;">
            <h2 style="color: #333;">Xin chào!</h2>
            
            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                Bạn đã yêu cầu đặt lại mật khẩu admin cho portfolio của bạn.
            </p>
            
            <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
                <p style="color: #333; font-weight: bold;">Click vào nút bên dưới để đặt lại mật khẩu:</p>
                <a href="' . $resetLink . '" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; margin: 10px 0;">
                    🔑 Đặt lại mật khẩu
                </a>
                <p style="color: #888; font-size: 14px; margin-top: 15px;">
                    Hoặc sao chép link này: <br>
                    <code style="background: #f1f1f1; padding: 5px; border-radius: 3px; word-break: break-all;">' . $resetLink . '</code>
                </p>
            </div>
            
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="color: #856404; margin: 0;">
                    <strong>⚠️ Lưu ý:</strong> Link này chỉ có hiệu lực trong 1 giờ. Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.
                </p>
            </div>
            
            <p style="color: #555; font-size: 14px;">
                Trân trọng,<br>
                <strong>Portfolio Admin System</strong>
            </p>
        </div>
        
        <div style="background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;">
            <p>© 2024 Portfolio Admin. All rights reserved.</p>
            <p>Nếu bạn có thắc mắc, vui lòng liên hệ quản trị viên.</p>
        </div>
    </div>';
    
    return sendEmail($to, $subject, $body);
}

function sendNewPasswordEmail($to, $newPassword) {
    $subject = 'Mật khẩu Admin mới - Portfolio';
    
    $body = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 20px; text-align: center; color: white;">
            <h1 style="margin: 0;">🔑 Mật khẩu mới</h1>
        </div>
        
        <div style="padding: 30px; background: #f8f9fa;">
            <h2 style="color: #333;">Xin chào!</h2>
            
            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                Mật khẩu admin mới của bạn đã được tạo thành công.
            </p>
            
            <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
                <p style="color: #333; font-weight: bold;">Mật khẩu mới của bạn:</p>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: monospace; font-size: 18px; letter-spacing: 2px; color: #dc3545;">
                    ' . $newPassword . '
                </div>
                <p style="color: #888; font-size: 14px;">
                    <strong>Lưu ý:</strong> Hãy đổi mật khẩu này ngay sau khi đăng nhập!
                </p>
            </div>
            
            <div style="background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="color: #0c5460; margin: 0;">
                    <strong>💡 Gợi ý:</strong> Sử dụng mật khẩu mạnh và duy nhất cho mỗi tài khoản. Tránh sử dụng lại mật khẩu ở nhiều nơi.
                </p>
            </div>
            
            <p style="color: #555; font-size: 14px;">
                Trân trọng,<br>
                <strong>Portfolio Admin System</strong>
            </p>
        </div>
        
        <div style="background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;">
            <p>© 2024 Portfolio Admin. All rights reserved.</p>
            <p>Nếu bạn có thắc mắc, vui lòng liên hệ quản trị viên.</p>
        </div>
    </div>';
    
    return sendEmail($to, $subject, $body);
}