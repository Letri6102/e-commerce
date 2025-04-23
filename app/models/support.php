<?php
class support {
    public function startTime() {
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        return date("H:i:s d-m-Y");
    }

    public function getDateNow() {
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        return date("d-m-Y");
    }

    public function sort($res) {
        usort($res, function($a, $b) {
            return intval($b['id']) <=> intval($a['id']);
        });       
        return $res;
    }

    public function handle_charge($MaGiamGia, $TienGiam, $DieuKien, $tienHang, $phiVanChuyen, $PhuongThucThanhToan, $sex) {
        if (!$this->checkCondition($DieuKien, $sex, $PhuongThucThanhToan, $tienHang)) {
            return ['success' => false, 'message' => 'Không thể áp mã giảm giá này cho đơn hàng'];
        }
        $lastChar = substr($MaGiamGia, -1);
        switch ($lastChar) {
            case 'K':
                $tienHang -= $TienGiam;
                break;
            case '%':
                $TienGiam = $tienHang * $TienGiam / 100;
                $tienHang -= $TienGiam;
                break;
            default:
                if ($MaGiamGia == 'GIAMCOD') $tienHang -= $TienGiam;
                elseif ($MaGiamGia == 'FREESHIP') {
                    $TienGiam = $phiVanChuyen;
                    $phiVanChuyen = 0;
                }
                else return ['success'=> false,'message'=> 'Mã giảm giá không hợp lệ'];
                break;
        }
        return ['success' => true, 'so_tien_da_giam' => $TienGiam, 'tong_tien_phai_tra' => $this->charge_total($tienHang, $phiVanChuyen)];
    }

    private function checkCondition($DieuKien, $sex, $PhuongThucThanhToan, $tienHang) {
        switch ($DieuKien) {
            case 'Tất cả':
                return true;
            case 'Male':
                if ($sex == $DieuKien) return true;
                return false;
            case 'Female':
                if ($sex == $DieuKien) return true;
                return false;
            case 'Chẵn':
                if ($this->isEvenDay()) return true;
                return false;
            case 'Lẻ':
                if (!$this->isEvenDay()) return true;
                return false;
            case 'COD':
                if ($PhuongThucThanhToan == 'COD') return true;
                return false;
            default:
                if (str_contains($DieuKien, '<') || str_contains($DieuKien, '>') || str_contains($DieuKien, '=')) {
                    preg_match('/(>=|<=|>|<|=)\s*(\d+)/', $DieuKien, $matches);
                    if ($matches) {
                        $operator = $matches[1];
                        $value = intval($matches[2]);
                        return $this->relop($operator, $value, $tienHang);
                    }
                } 
                else return false;
        }
    }

    private function relop($operator, $value, $tienHang) {
        if ($operator == '<' && $tienHang < $value) return true;
        elseif ($operator == '>' && $tienHang > $value) return true;
        elseif ($operator == '>=' && $tienHang >= $value) return true;
        elseif ($operator == '<=' && $tienHang <= $value) return true;
        elseif ($operator == '=' && $tienHang == $value) return true;
        return false;
    }

    private function charge_total($tienHang, $phiVanChuyen) {
        return $tienHang + $phiVanChuyen;
    }

    private function isEvenDay() {
        $day = date('j');
        return $day % 2 === 0;
    }

    public function deleteDirectory($dir) {
        if (!is_dir($dir)) {
            return;
        }
    
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $filePath = "$dir/$file";
            if (is_dir($filePath)) {
                deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }
        rmdir($dir);
    }
}
?>