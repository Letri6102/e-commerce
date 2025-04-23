    DROP DATABASE IF EXISTS bkstore;
    -- Create the database
    CREATE DATABASE bkstore;
    USE bkstore;

    -- Table for LOGIN
    CREATE TABLE LOGIN (
        UID INT AUTO_INCREMENT PRIMARY KEY,
        Email VARCHAR(255) NOT NULL,
        Password VARCHAR(255) NOT NULL,
        Role ENUM('Customer', 'Admin') NOT NULL,
        Ten VARCHAR(255) NOT NULL,
        Avatar TEXT,
        UNIQUE (Email)
    );

    -- Table for ADMIN
    CREATE TABLE ADMIN (
        UID INT PRIMARY KEY,
        FOREIGN KEY (UID) REFERENCES LOGIN(UID)
    );

    -- Table for LỊCH_SỬ_ĐĂNG_NHẬP
    CREATE TABLE LICH_SU_DANG_NHAP (
        MaLog INT AUTO_INCREMENT PRIMARY KEY,
        UID INT,
        ThoiGian TEXT,
        NoiDung TEXT,
        FOREIGN KEY (UID) REFERENCES LOGIN(UID)
    );

    -- Table for KHÁCH_HÀNG
    CREATE TABLE KHACH_HANG (
        UID INT PRIMARY KEY,
        GioiTinh ENUM('Male', 'Female'),
        SDT TEXT,
        DiaChi TEXT,
        TrangThai ENUM('Đang hoạt động', 'Bị cấm') DEFAULT 'Đang hoạt động',
        FOREIGN KEY (UID) REFERENCES LOGIN(UID)
    );

-- Table for SẢN_PHẨM
CREATE TABLE SAN_PHAM (
    ID_SP INT AUTO_INCREMENT PRIMARY KEY,
    TenSP VARCHAR(255) NOT NULL,
    MoTa TEXT,
    Gia INT NOT NULL,
    TyLeGiamGia FLOAT,
    SoLuongKho INT NOT NULL,
    NXB VARCHAR(255),
    KichThuoc VARCHAR(255),
    SoTrang INT,
    PhanLoai VARCHAR(255),
    TuKhoa VARCHAR(255),
    HinhThuc VARCHAR(255),
    TacGia VARCHAR(255),
    NgonNgu VARCHAR(255),
    NamXB INT,
    TrangThai ENUM('Đang hiện', 'Đang ẩn', 'Đã xoá') DEFAULT 'Đang hiện'
);

    CREATE TABLE HINH_ANH (
        ID_HinhAnh INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        Anh TEXT,
        ID_SP INT,
        FOREIGN KEY (ID_SP) REFERENCES SAN_PHAM(ID_SP)
    );

    -- Table for SẢN_PHẨM_ĐỀ_XUẤT
    CREATE TABLE SAN_PHAM_DE_XUAT (
        MaDeXuat INT AUTO_INCREMENT PRIMARY KEY,
        TenSP VARCHAR(255) NOT NULL,
        NoiDung TEXT,
        TrangThai ENUM("Đang chờ duyệt", "Đã duyệt", "Đã từ chối") DEFAULT "Đang chờ duyệt",
        GhiChu TEXT,
        UID INT,
        NgayYeuCau TEXT,
        FOREIGN KEY (UID) REFERENCES KHACH_HANG(UID)
    );

    -- Table for ĐÁNH_GIÁ
    CREATE TABLE DANH_GIA (
        MaDanhGia INT AUTO_INCREMENT PRIMARY KEY,
        ID_SP INT,
        UID INT,
        NgayDanhGia TEXT NOT NULL,
        SoSao INT CHECK (SoSao BETWEEN 1 AND 5),
        NoiDung TEXT,
        TrangThai ENUM("Đang hiện", "Đang ẩn", "Đã xóa") DEFAULT "Đang hiện",
        PhanHoi TEXT,
        FOREIGN KEY (ID_SP) REFERENCES SAN_PHAM(ID_SP),
        FOREIGN KEY (UID) REFERENCES KHACH_HANG(UID)
    );

    -- Table for BÌNH_LUẬN
    CREATE TABLE BINH_LUAN (
        MaBinhLuan INT AUTO_INCREMENT PRIMARY KEY,
        ID_SP INT,
        UID INT,
        NgayBinhLuan TEXT NOT NULL,
        NoiDung TEXT,
        PhanHoi TEXT DEFAULT "",
        TrangThai ENUM("Đang hiện", "Đang ẩn", "Đã xóa") DEFAULT "Đang hiện",
        FOREIGN KEY (ID_SP) REFERENCES SAN_PHAM(ID_SP),
        FOREIGN KEY (UID) REFERENCES KHACH_HANG(UID)
    );

    -- Table for MÃ_GIẢM_GIÁ
    CREATE TABLE MA_GIAM_GIA (
        ID_GiamGia INT AUTO_INCREMENT PRIMARY KEY,
        Ma VARCHAR(50) UNIQUE,
        TienGiam DECIMAL(10, 2) NOT NULL,
        DieuKien TEXT,
        SoLuong INT,
        TrangThai ENUM("Kích hoạt", "Hết hạn", "Đã xóa") DEFAULT "Kích hoạt"
    );

    -- Table for ĐƠN_HÀNG
    CREATE TABLE DON_HANG (
        ID_DonHang INT AUTO_INCREMENT PRIMARY KEY,
        UID INT,
        NgayDat TEXT,  -- Changed TEXT to DATE
        TongTien DECIMAL(10, 2) NOT NULL,
        MaGiamGia VARCHAR(50),
        TrangThai ENUM("Chờ xác nhận", "Đã xác nhận", "Đang vận chuyển", "Đã giao hàng", "Đã hủy") DEFAULT "Chờ xác nhận",
        SDT TEXT,
        DiaChi TEXT,
        ThanhToan ENUM("Chưa thanh toán", "Đã thanh toán", "Huỷ thanh toán") DEFAULT "Chưa thanh toán", -- Trang thai thanh toan
        TenNguoiNhan TEXT,
        PhuongThucThanhToan ENUM('COD', 'Bank') DEFAULT 'COD',  -- Consistent single quotes
        HoaDon DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (UID) REFERENCES LOGIN(UID),
        FOREIGN KEY (MaGiamGia) REFERENCES MA_GIAM_GIA(Ma) ON UPDATE CASCADE
    );

    -- Table for GỒM
    CREATE TABLE GOM (
        ID_DonHang INT,
        ID_SP INT,
        SoLuong INT,
        PRIMARY KEY (ID_DonHang, ID_SP),
        FOREIGN KEY (ID_DonHang) REFERENCES DON_HANG(ID_DonHang),
        FOREIGN KEY (ID_SP) REFERENCES SAN_PHAM(ID_SP)
    );

    -- Table for TRONG_GIO_HANG
    CREATE TABLE TRONG_GIO_HANG (
        UID INT,
        ID_SP INT,
        SoLuong INT,
        PRIMARY KEY (UID, ID_SP),
        FOREIGN KEY (UID) REFERENCES LOGIN(UID),
        FOREIGN KEY (ID_SP) REFERENCES SAN_PHAM(ID_SP)
    );

    -- Table for THÍCH
    CREATE TABLE THICH (
        UID INT,
        ID_SP INT,
        PRIMARY KEY (UID, ID_SP),
        FOREIGN KEY (UID) REFERENCES KHACH_HANG(UID),
        FOREIGN KEY (ID_SP) REFERENCES SAN_PHAM(ID_SP)
    );

    -- Table for THÔNG_BÁO
    CREATE TABLE THONG_BAO (
        MaThongBao INT AUTO_INCREMENT PRIMARY KEY,
        UID INT,
        NoiDung TEXT,
        TrangThai ENUM('Unread', 'Read') DEFAULT "Unread",
        Type ENUM('Yêu cầu', 'Đơn hàng'),
        NgayThongBao TEXT,
        FOREIGN KEY (UID) REFERENCES LOGIN(UID)
    );

    -- Table for LOẠI THÔNG_BÁO
    CREATE TABLE LOAI_THONG_BAO (
        Id INT AUTO_INCREMENT PRIMARY KEY,
        MaThongBao INT,
        ID_DonHang INT,
        MaDeXuat INT,
        FOREIGN KEY (MaThongBao) REFERENCES THONG_BAO(MaThongBao),
        FOREIGN KEY (ID_DonHang) REFERENCES DON_HANG(ID_DonHang),
        FOREIGN KEY (MaDeXuat) REFERENCES SAN_PHAM_DE_XUAT(MaDeXuat)
    );

    -- Bảng TIN_TUC
    CREATE TABLE TIN_TUC (
        MaTinTuc INT AUTO_INCREMENT PRIMARY KEY,
        TieuDe VARCHAR(255),
        ThoiGianTao TEXT,
        NoiDung TEXT,
        TuKhoa VARCHAR(255),
        TrangThai ENUM("Đang hiện", "Đang ẩn") DEFAULT "Đang hiện",
        MoTa TEXT
    );

    CREATE TABLE ANH_MINH_HOA (
        MaAnh INT AUTO_INCREMENT PRIMARY KEY,
        MoTa TEXT,
        LinkAnh TEXT,
        MaTinTuc INT,
        FOREIGN KEY (MaTinTuc) REFERENCES TIN_TUC(MaTinTuc)
    );

    -- Bảng BANNER
    CREATE TABLE BANNER (
        MaBanner INT AUTO_INCREMENT PRIMARY KEY,
        Image VARCHAR(255),
        IdSP INT,
        MoTa TEXT,
        TrangThai ENUM("Đang hiện", "Đang ẩn") DEFAULT "Đang hiện",
        FOREIGN KEY (IdSP) REFERENCES SAN_PHAM(ID_SP)
    );

    -- Bảng DOI_TAC
    CREATE TABLE DOI_TAC (
        MaDoiTac INT AUTO_INCREMENT PRIMARY KEY,
        Ten VARCHAR(255),
        HinhAnh TEXT,
        LienKet VARCHAR(255),
        TrangThai ENUM("Đang hiện", "Đang ẩn") DEFAULT "Đang hiện"
    );

    -- Bảng HE_THONG
    CREATE TABLE HE_THONG (
        MaHeThong INT PRIMARY KEY,
        TrangThaiBaoTri BOOLEAN,
        TuKhoa TEXT,
        ClientID TEXT,
        APIKey TEXT,
        Checksum TEXT
    );

    -- Bảng THONG_TIN_LIEN_HE
    CREATE TABLE THONG_TIN_LIEN_HE (
        MaThongTin INT AUTO_INCREMENT PRIMARY KEY,
        Loai VARCHAR(255),
        ThongTin TEXT,
        HinhAnh VARCHAR(255),
        TrangThai ENUM("Đang hiện", "Đang ẩn") DEFAULT "Đang hiện"
    );

    -- Bảng MANG_XA_HOI
    CREATE TABLE MANG_XA_HOI (
        MaMXH INT AUTO_INCREMENT PRIMARY KEY,
        HinhAnh VARCHAR(255),
        LienKet VARCHAR(255),
        TrangThai ENUM("Đang hiện", "Đang ẩn") DEFAULT "Đang hiện"
    );