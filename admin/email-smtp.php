<?php
// Email helper với SMTP thực sự cho Gmail
function sendSMTPEmail($to, $subject, $body, $debug = false) {
    global $config;
    
    if (!isset($config['email']) || !$config['email']['enabled']) {
        return ['success' => false, 'error' => 'Email chưa được cấu hình'];
    }
    
    try {
        // SMTP server settings
        $smtp_host = $config['email']['smtp_host'];
        $smtp_port = $config['email']['smtp_port'];
        $smtp_username = $config['email']['smtp_username'];
        $smtp_password = $config['email']['smtp_password'];
        $smtp_encryption = $config['email']['smtp_encryption'] ?? 'tls';
        
        // Điều chỉnh port theo encryption type
        if ($smtp_port == 587 && $smtp_encryption === 'tls') {
            // Đúng như cấu hình
        } elseif ($smtp_port == 465 && $smtp_encryption === 'ssl') {
            // Đúng như cấu hình
        } elseif ($smtp_port == 25) {
            $smtp_encryption = 'none';
        }
        
        $from_email = $config['email']['from_email'];
        $from_name = $config['email']['from_name'];
        
        if ($debug) {
            echo "=== SMTP DEBUG ===\n";
            echo "Connecting to: $smtp_host:$smtp_port\n";
            echo "Username: $smtp_username\n";
            echo "Encryption: $smtp_encryption\n";
            echo "From: $from_name <$from_email>\n";
            echo "To: $to\n";
            echo "Subject: $subject\n";
        }
        
        // Kết nối SMTP
        $timeout = 30;
        $errno = 0;
        $errstr = '';
        
        // Xử lý hostname cho EHLO
        $hostname = 'localhost';
        if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
            $hostname = $_SERVER['HTTP_HOST'];
        } elseif (isset($_SERVER['SERVER_NAME']) && !empty($_SERVER['SERVER_NAME'])) {
            $hostname = $_SERVER['SERVER_NAME'];
        }
        
        // Kết nối với encryption phù hợp
        if ($smtp_encryption === 'ssl') {
            $smtp_host = 'ssl://' . $smtp_host;
        }
        
        $socket = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, $timeout);
        
        if (!$socket) {
            return ['success' => false, 'error' => "Không thể kết nối SMTP: $errstr ($errno)"];
        }
        
        if ($debug) echo "Connected to SMTP server\n";
        
        // Đọc greeting
        $response = fgets($socket, 515);
        if ($debug) echo "Server: $response";
        
        // EHLO/HELO
        $hello = "EHLO " . $hostname;
        fputs($socket, $hello . "\r\n");
        if ($debug) echo "Client: $hello\n";
        
        while ($line = fgets($socket, 515)) {
            if ($debug) echo "Server: $line";
            if (substr($line, 3, 1) === ' ') break;
        }
        
        // STARTTLS nếu cần
        if ($smtp_encryption === 'tls' && $smtp_port == 587) {
            fputs($socket, "STARTTLS\r\n");
            if ($debug) echo "Client: STARTTLS\n";
            $response = fgets($socket, 515);
            if ($debug) echo "Server: $response";
            
            if (substr($response, 0, 3) != '220') {
                fclose($socket);
                return ['success' => false, 'error' => 'STARTTLS thất bại'];
            }
            
            // Enable crypto
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($socket);
                return ['success' => false, 'error' => 'Không thể bật mã hóa TLS'];
            }
            
            // EHLO lại sau TLS
            fputs($socket, $hello . "\r\n");
            if ($debug) echo "Client: $hello (after TLS)\n";
            
            while ($line = fgets($socket, 515)) {
                if ($debug) echo "Server: $line";
                if (substr($line, 3, 1) === ' ') break;
            }
        }
        
        // Authentication
        fputs($socket, "AUTH LOGIN\r\n");
        if ($debug) echo "Client: AUTH LOGIN\n";
        $response = fgets($socket, 515);
        if ($debug) echo "Server: $response";
        
        if (substr($response, 0, 3) != '334') {
            fclose($socket);
            return ['success' => false, 'error' => 'AUTH LOGIN thất bại'];
        }
        
        // Username
        fputs($socket, base64_encode($smtp_username) . "\r\n");
        if ($debug) echo "Client: [USERNAME]\n";
        $response = fgets($socket, 515);
        if ($debug) echo "Server: $response";
        
        if (substr($response, 0, 3) != '334') {
            fclose($socket);
            return ['success' => false, 'error' => 'Username không hợp lệ'];
        }
        
        // Password
        fputs($socket, base64_encode($smtp_password) . "\r\n");
        if ($debug) echo "Client: [PASSWORD]\n";
        $response = fgets($socket, 515);
        if ($debug) echo "Server: $response";
        
        if (substr($response, 0, 3) != '235') {
            fclose($socket);
            return ['success' => false, 'error' => 'Mật khẩu không hợp lệ hoặc tài khoản bị khóa'];
        }
        
        // Mail FROM
        fputs($socket, "MAIL FROM:<$from_email>\r\n");
        if ($debug) echo "Client: MAIL FROM:<$from_email>\n";
        $response = fgets($socket, 515);
        if ($debug) echo "Server: $response";
        
        if (substr($response, 0, 3) != '250') {
            fclose($socket);
            return ['success' => false, 'error' => 'MAIL FROM bị từ chối'];
        }
        
        // RCPT TO
        fputs($socket, "RCPT TO:<$to>\r\n");
        if ($debug) echo "Client: RCPT TO:<$to>\n";
        $response = fgets($socket, 515);
        if ($debug) echo "Server: $response";
        
        if (substr($response, 0, 3) != '250') {
            fclose($socket);
            return ['success' => false, 'error' => 'RCPT TO bị từ chối - Email đích không hợp lệ'];
        }
        
        // DATA
        fputs($socket, "DATA\r\n");
        if ($debug) echo "Client: DATA\n";
        $response = fgets($socket, 515);
        if ($debug) echo "Server: $response";
        
        if (substr($response, 0, 3) != '354') {
            fclose($socket);
            return ['success' => false, 'error' => 'DATA bị từ chối'];
        }
        
        // Tạo email headers và body
        $email_headers = "From: $from_name <$from_email>\r\n";
        $email_headers .= "To: $to\r\n";
        $email_headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $email_headers .= "Date: " . date('r') . "\r\n";
        $email_headers .= "MIME-Version: 1.0\r\n";
        $email_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $email_headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
        
        // Tạo message ID
        $message_id = '<' . md5(uniqid()) . '@' . $hostname . '>';
        $email_headers .= "Message-ID: $message_id\r\n";
        
        // Chuẩn bị body
        $quoted_body = quoted_printable_encode($body);
        
        // Gửi email content
        fputs($socket, $email_headers . "\r\n" . $quoted_body . "\r\n.\r\n");
        if ($debug) {
            echo "Client: [EMAIL CONTENT - " . strlen($email_headers . $quoted_body) . " bytes]\n";
        }
        
        $response = fgets($socket, 515);
        if ($debug) echo "Server: $response";
        
        if (substr($response, 0, 3) != '250') {
            fclose($socket);
            return ['success' => false, 'error' => 'Gửi email thất bại: ' . $response];
        }
        
        // QUIT
        fputs($socket, "QUIT\r\n");
        if ($debug) echo "Client: QUIT\n";
        $response = fgets($socket, 515);
        if ($debug) echo "Server: $response";
        
        fclose($socket);
        
        return ['success' => true, 'message' => 'Email đã được gửi thành công qua SMTP'];
        
    } catch (Exception $e) {
        if (isset($socket) && is_resource($socket)) {
            fclose($socket);
        }
        return ['success' => false, 'error' => 'Lỗi SMTP: ' . $e->getMessage()];
    }
}

// Hàm gửi email reset password với SMTP
function sendPasswordResetSMTPEmail($to, $resetLink, $debug = false) {
    global $config;
    
    $subject = 'Đặt lại mật khẩu Admin - Portfolio';
    
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
    
    return sendSMTPEmail($to, $subject, $body);
}