    USE bkstore;

    INSERT INTO LOGIN (Email, Password, Role, Ten, Avatar)
    VALUES
    ('user1@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Customer', 'User One', NULL),
    ('user2@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Customer', 'User Two', NULL),
    ('user3@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Customer', 'User Three', NULL),
    ('admin1@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Admin', 'Admin One', NULL),
    ('admin2@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Admin', 'Admin Two', NULL),
    ('user4@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Customer', 'User Four', NULL),
    ('user5@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Customer', 'User Five', NULL),
    ('admin3@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Admin', 'Admin Three', NULL),
    ('user6@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Customer', 'User Six', NULL),
    ('user7@example.com', '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy', 'Customer', 'User Seven', NULL),
    ('tai.tranthanh@hcmut.edu.vn', '$2y$10$92USNqUYOyy0nArIY1LKouJDAWwVUh3TmNlcA4Oh3T6HjTCXc7VKS', 'Admin', 'Trần Thành Tài', NULL);

    INSERT INTO ADMIN (UID)
    VALUES
    (4),  -- Corresponds to 'admin1@example.com'
    (5),  -- Corresponds to 'admin2@example.com'
    (8),  -- Corresponds to 'admin3@example.com'
    (11);

    INSERT INTO LICH_SU_DANG_NHAP (UID, ThoiGian, NoiDung)
    VALUES
    (1, '10:00:00 21-10-2023', 'Đăng nhập thành công từ IP 192.168.1.1'),
    (2, '10:05:00 21-10-2023', 'Đăng nhập thành công từ IP 192.168.1.2'),
    (3, '10:10:00 21-10-2023', 'Đăng nhập thành công từ IP 192.168.1.3'),
    (4, '10:15:00 21-10-2023', 'Đăng nhập thất bại từ IP 192.168.1.4'),
    (5, '10:20:00 21-10-2023', 'Đăng nhập thành công từ IP 192.168.1.5'),
    (6, '10:25:00 21-10-2023', 'Đăng nhập thất bại từ IP 192.168.1.6'),
    (7, '10:30:00 21-10-2023', 'Đăng nhập thành công từ IP 192.168.1.7'),
    (8, '10:35:00 21-10-2023', 'Đăng nhập thành công từ IP 192.168.1.8'),
    (9, '10:40:00 21-10-2023', 'Đăng nhập thất bại từ IP 192.168.1.9'),
    (10, '10:45:00 21-10-2023', 'Đăng nhập thành công từ IP 192.168.1.10');

    INSERT INTO KHACH_HANG (UID, GioiTinh, SDT, DiaChi, TrangThai)
    VALUES
    (1, 'Male', '0123456789', '123 Main St', 'Đang hoạt động'),
    (2, 'Female', '0987654321', '456 Oak Ave', 'Đang hoạt động'),
    (3, 'Male', '0123456789', '789 Pine Rd', 'Đang hoạt động'),
    (4, 'Male', '0123456789', '789 Pine Rd', 'Đang hoạt động'),
    (5, 'Male', '0123456789', '789 Pine Rd', 'Đang hoạt động'),
    (6, 'Female', '0987654321', '101 Birch Ln', 'Đang hoạt động'),
    (7, 'Male', '0123456789', '202 Cedar St', 'Đang hoạt động'),
    (8, 'Male', '0123456789', '789 Pine Rd', 'Đang hoạt động'),
    (9, 'Female', '0987654321', '303 Maple Ave', 'Đang hoạt động'),
    (10, 'Male', '0123456789', '404 Elm St', 'Đang hoạt động'),
    (11, 'Male', '0123456789', '404 Elm St', 'Đang hoạt động');

    INSERT INTO `SAN_PHAM` (`ID_SP`, `TenSP`, `MoTa`, `Gia`, `TyLeGiamGia`, `SoLuongKho`, `NXB`, `KichThuoc`, `SoTrang`, `PhanLoai`, `TuKhoa`, `HinhThuc`, `TacGia`, `NgonNgu`, `NamXB`) VALUES
    (1, 'Văn Học Tuổi Hoa - Nhà', '\"Nhà\" là sự chắp nối những mảnh kí ức bé nhỏ, rời rạc của cô bé tên Liên đi tìm chính mình trong thời niên thiếu. Sống ở một thị trấn nhỏ, giữa bao biến chuyển, giao thoa cũ – mới ồ ạt những năm 90, Liên tò mò, choáng ngợp, hoài nghi về khát khao và vai trò của một người phụ nữ, để rồi tách mình khỏi những thứ cô bé cho là không đặc biệt. Cũng từ đây, Liên định nghĩa lại ước mong của mình và thử đón nhận tình yêu thương tưởng chừng luôn thiếu vắng.\n\n“Trong cơn mơ ngủ, có lẽ tôi đã đơn giản thiếp đi giữa lòng mẹ. Có lẽ tất cả cuộc đối thoại này chỉ là ảo giác sinh ra từ tâm trí mệt mỏi của tôi. Một hình bóng trong mơ, một sự tồn tại trong tiềm thức của tôi đã nhẹ nhàng đến thế chỗ bà… Như thể, điều tôi tìm kiếm mãi mãi, từ lúc ra đời, chỉ đơn giản là quay trở lại trạng thái này. Rằng bao lâu, tôi đã khao khát, chờ đợi, tìm kiếm chính điều này đây.”\n\n', 65000, 0.13, 50, 'NXB Kim Đồng', '19 x 13 x 1.4 cm', 280, 'Sách Văn Học', 'Thiếu nhi, giáo dục', 'Bìa mềm', '	\nNguyễn Dương Quỳnh', 'Tiếng Việt', 2024),
    (2, 'Văn Học Tuổi Hoa - Nhặt', 'Cậu bé Tính có vẻ như “chẳng ra ngô khoai, hồn vía gì”, nhưng tình cảm lại vô vùng sâu sắc.\n\nCậu NHẶT khoảnh khắc vụng về bố cắt mái tóc “bum bê” tròn ủng cho bà với những câu nói đùa đầy yêu thương, NHẶT chuyện vụn vặt nấu bát canh hoa thiên lí, om nồi ốc chuối đậu… ăm ắp không khí gia đình đầm ấm, NHẶT sự vụng về “cười đến đau cả mép” của chị Thương “ư ơ ngờ ương”… NHẶT mọi thứ, để làm hành trang.\n\nMỗi thứ cậu NHẶT, dù thích hay ghét cũng đong đầy những yêu thương và kỉ niệm, những lo toan và chăm chút khiến ta thấy trân trọng hơn cuộc sống và tình cảm của người thân.', 35000, 0.25, 30, 'NXB Kim Đồng', '19 x 13 x 0.6 cm', 132, 'Sách Văn Học', 'Văn học, lãng mạn', 'Bìa Mềm', 'Nguyễn Mỹ Nữ', 'Tiếng Việt', 2023),
    (3, 'Văn Học Thiếu Nhi - Kho Báu Trong Thành Phố', 'Sau JONI MẶT TỊT VÀ ĐỒNG BỌN TINH NGHỊCH kể về chú mèo bông xù rất được lòng bạn đọc trẻ, KHO BÁU TRONG THÀNH PHỐ tiếp tục là một truyện dài đầy yêu thương và gợi nhớ của tác giả Nguyễn Khắc Cường, diễn ra trong lòng Sài Gòn - Thành phố Hồ Chí Minh. Theo chân cuộc đua đi tìm kho báu của cha con bé Kiến, những hồi ức về Thành phố ở thập niên 80 hiện ra sống động, với những khung cảnh vừa quen vừa lạ. Truyện còn là một \"bộ sưu tầm\" những trò chơi dân gian đã từng làm mê mệt trẻ em cách đây mấy mươi năm: chọi dế, thả thuyền, bắn bi, búng thun… được tác giả mô tả hài hước, chân thực.\n\nVì lẽ đó, tác phẩm này không chỉ dành cho trẻ em, mà người lớn cũng có thể đọc say mê và mỉm cười với một bầu trời tuổi thơ cũ đang trở lại.\n\n--\n\n\"Hình như lúc còn nhỏ ai cũng mong mình lớn nhanh để khỏi bị bắt ngủ trưa, để được đi chơi tự do, muốn ăn bao nhiêu bánh kẹo tùy thích... Chừng lớn lên, nhiều người lại mơ được nhỏ lại, được tung tăng chạy nhảy vô tư.\n\nDĩ nhiên ước mơ nhỏ lại đâu thể nào thành hiện thực, nên tôi viết cuốn truyện này để được quay về tuổi thơ, sa đà vô các trò chơi búng thun, tạt lon, bắn bi, chọi cầu, đá cá, bắt dế, tắm mưa, lò cò, chơi u, chơi keng, đánh trỏng, banh lỗ... Những trò chơi khiến trẻ con 40 năm trước mê mệt, quên ăn quên ngủ, giờ không thấy ai chơi nữa, buồn quá chừng!\n\nKhông mơ nhỏ lại được thì tôi ước những trò chơi bị ngủ quên đó mau thức dậy, làm bạn với trẻ em bây giờ. Khi cùng nhau chạy nhảy, la hét, rượt đuổi... các em sẽ trở nên lanh tay lẹ mắt, hoạt bát, nhanh nhẹn, ít nguy cơ bị cận thị, béo phì hơn là suốt ngày ngồi một chỗ dán mắt vô màn hình máy tính, điện thoại.\"', 105000, 0.16, 25, 'NXB Trẻ', '20 x 13 x 1.2 cm', 252, 'Sách Văn Học', 'Giáo dục, khoa học', 'Bìa mềm', '	\nNguyễn Khắc Cường', 'Tiếng Việt', 2023),
    (4, 'Danh Tác Văn Học Việt Nam - Ngày Mới', '*Trích đoạn:\n\n\"Trường khẽ thở dài. Trong cái vui của lòng chàng, Trường muốn cho anh chị cũng được sung sướng. Chàng hiểu những nỗi đau đớn mà Dung đã phải chịu vì thái độ lãnh đạm của Xuân. Tuy vậy, giờ nghĩ đến anh, Trường không thấy bực tức như trước nữa. Sự thay đổi của Xuân gần đây, chàng đã đoán biết những duyên cớ. Có lẽ Xuân cũng đã có những hành vi và ý nghĩ của mình. Xuân, không có can đảm trở về với cuộc đời chàng có, với những vui và những buồn của sự sống hằng ngày.\"', 85000, 0.23, 40, 'NXB Văn Học', '20.5 x 15 x 1.2 cm', 400, 'Sách Văn Học', 'Lịch sử, văn hóa', 'Bìa mềm', 'Thạch Lam', 'Tiếng Việt', 2023),
    (5, 'Con Đường Văn Sĩ', 'Cùng với những độc giả yêu văn chương, muốn tìm hiểu thêm về nhà văn Nguyễn Huy Tưởng, cuốn sách đặc biệt nhắm đến các bạn đọc trẻ tuổi: Những người đang háo hức và băn khoăn, quả quyết và khắc khoải bước vào đời với khát khao lập thân lập nghiệp giống như bậc tiền nhân của mình…\n\n---\n\nNhà văn NGUYỄN HUY TƯỞNG sinh ngày 6.5.1912 trong một gia đình Nho giáo ở làng Dục Tú, Từ Sơn, Bắc Ninh (nay thuộc xã Dục Tú, huyện Đông Anh, Hà Nội). Những năm tháng tuổi trẻ, ông tham gia các phong trào yêu nước của thanh niên, học sinh ở Hải Phòng; hoạt động Truyền bá quốc ngữ, Hướng đạo sinh. Năm 1943 ông gia nhập nhóm Văn hóa cứu quốc bí mật. Tháng 8.1945, Nguyễn Huy Tưởng được cử tham dự Đại hội quốc dân ở Tân Trào. Cách mạng tháng Tám thành công, ông trở thành người lãnh đạo chủ chốt của hội Văn hóa cứu quốc và là đại biểu Quốc hội khóa 1 năm 1946. Sau 1954, ông là thành viên sáng lập Hội Nhà văn Việt Nam, Uỷ viên Ban chấp hành. Nguyễn Huy Tưởng là một trong những người sáng lập và là giám đốc đầu tiên của Nhà xuất bản Kim Đồng. Do mắc bệnh hiểm nghèo, ông mất ngày 25.7.1960 tại Hà Nội.\n\nTrong cuộc đời sáng tác văn chương, nhà văn Nguyễn Huy Tưởng đã đoạt nhiều giải thưởng, trong đó có Giải thưởng Hồ Chí Minh về Văn học Nghệ thuật, đợt I, năm 1996.', 180000, 0.13, 20, 'NXB Kim Đồng', '22.5 x 14 x 0.5 cm', 584, 'Sách Văn Học', 'Lịch sử, văn hóa', 'Bìa mềm', 'Nguyễn Huy Tưởng', 'Tiếng Việt', 2024),
    (6, 'Học Cách Chiến Thắng Sự Tự Ti', 'Mỗi lần trẻ có tâm trạng lo lắng, rụt rè khi tham gia một hoạt động nào đó, chắc hẳn rất nhiều bố mẹ thường cổ vũ, động viên: “Hãy tự tin, mạnh mẽ lên con nhé!”\r\n\r\nNhưng làm thế nào để trẻ trở nên tự tin, mạnh mẽ? Làm thế nào để trẻ không nhút nhát, rụt rè, biết yêu thương bản thân và mọi người xung quanh? Làm thế nào để trẻ dũng cảm đối diện với những việc không như ý muốn bằng thái độ bình tĩnh, không cáu gắt? Làm thế nào để trẻ tích cực phát huy ưu điểm, ngày càng hoàn thiện bản thân?\r\n\r\nCác bố mẹ hãy cùng con đọc bộ sách Bồi dưỡng tính cách tự tin và mạnh mẽ cho trẻ để đi tìm lời giải cho những băn khoăn kể trên nhé. Thông qua tám câu chuyện nhỏ thú vị cùng hình minh họa ngộ nghĩnh về các bạn động vật đáng yêu, tin rằng các bé sẽ học được nhiều điều bổ ích lắm đấy', 32000, 0.18, 20, 'NXB Thanh Niên', '20.5 x 19.6 x 0.2 cm', 20, 'Sách Thiếu Nhi', 'Thiếu nhi', 'Bìa mềm', 'Sách Thiếu Nhi Tiểu Kỳ Lân', 'Tiếng Việt', 2023),
    (7, 'Đời Thừa - Danh Tác Văn Học Việt Nam', 'Trong mảng sáng tác về đề tài tiểu tư sản của Nam Cao, truyện ngắn \"Đời Thừa\" có một vị trí đặc biệt. \"Đời Thừa\" đã ghi lại chân thật hình ảnh buồn thảm của người tri thức tiểu tư sản nghèo, nhà văn Nam Cao đã phác hoạ rõ nét hình ảnh vừa bi vừa hài của lớp người này trở nên đầy ám ảnh. Giá trị của \"Đời Thừa\" không phải chỉ ở chỗ đã miêu tả chân thật cuộc sống nghèo khổ, bế tắc của người trí thức tiểu tư sản nghèo, đã viết về người tiểu tư sản không phải với ngòi bút vuốt ve, thi vị hoá, mà còn vạch ra cả những thói xấu của họ v.v....', 70000, 0.23, 0, 'NXB Văn Học', '20.5 x 14.5 x 1.1 cm', 222, 'Sách Văn Học', 'Văn Học', 'Bìa mềm', 'Nam Cao', 'Tiếng Việt', 2022),
    (8, 'Linh Điểu', 'Một tác phẩm của nhà văn Nguyễn Văn Học với sự rung cảm về thân phận mải miết bay trong nỗi cô đơn, lạc đàn tìm nơi dựng tổ\r\n\r\nMột tác phẩm phản ánh một hiện thực xã hội chất chứa đầy những ưu tư, trăn trở, những mối lo, nguy cơ về những sự tha hóa, đổ vỡ, bất công…\r\n\r\nTiểu thuyết kể về cô gái Diệp Vân với tấm lòng hướng thiện, yêu cuộc sống, yêu các loài chim chóc và bầu trời. Để thực hiện sứ mệnh của mình, cô đã ra sức bảo vệ vườn cò của bà ngoại, tích cực trồng cây xanh trên các quả đồi, trên những con đường quê và cảm hóa Hùng - một thanh niên bất cần đời. Song, những việc làm ấy không được xã hội công nhận, làm theo mà cũng như nhiều người khác, cuối cùng Diệp Vân lại phải chết vì sự tàn bạo của đám đông, bởi sự chỉ trích, miệt thị những người bị dị tật như cô.\r\n\r\nTrích đoạn:\r\n\r\n\"Những chùm chim trắng được sinh ra từ những chòm mây tinh tuyền. Mây quyện vào cánh chim tạo mối giao hòa huyền ảo. Cánh chim dìu bầu trời vào nhan sắc, như thể cả bầu trời chuẩn bị bước vào một trận ái ân\"', 98000, 0.23, 100, 'NXB Dân Trí', '13 x 20.5 x 1.5 cm', 303, 'Sách Văn Học', 'Văn Học', 'Bìa mềm', 'Nguyễn Văn Học', 'Tiếng Việt', 2020),
    (9, 'Bạn Văn Bạn Mình - Chân Dung Văn Học', 'Với Chân dung văn học, nhà văn Nguyễn Tuân vẫn luôn là cây bút hết mực tài hoa, trân trọng chữ nghĩa. Ông thường vận dụng con mắt tinh tường về cả điện ảnh, hội họa, sân khấu, âm nhạc... để quan sát, cảm thụ văn chương và đưa ra những nhận xét độc đáo, tế nhị về tác phẩm, tác giả. Cách thưởng văn của ông như nhâm nhi thưởng “Chén trà sương” và tinh tế phát hiện ra một phần tư vỏ trấu bị lẫn trong ấm trà ngon.\r\n\r\nTrong cuốn sách này, bạn sẽ được gặp các “Bạn Văn” của Nguyễn Tuân qua các bài: “Chén rượu vĩnh biệt”, “Một đêm họp đưa ma Phụng”, “Đốtxtôi”, “Đọc Sê-khốp”, “Thạch Lam”, “Truyện ngắn Lỗ Tấn”, “Thời và thơ Tú Xương”, “Gọi là gới thiệu thêm về Nguyên Hồng”, “Ký Hoàng Phủ Ngọc Tường có rất nhiều ảnh lửa”, “Phố Phái”…\r\n\r\nNhà văn Nguyễn Tuân (1910 -1987) quê ở thôn Thượng Đình, xã Nhân Mục (tên nôm là làng Mọc), nay thuộc phường Nhân Chính, quận Thanh Xuân, Hà Nội.\r\nNguyễn Tuân sinh ra và lớn lên trong một gia đình nhà Nho. Ông nổi tiếng từ những năm 1938, 1939 với tập du ký Một chuyến đi, tập truyện ngắn Vang bóng một thời...\r\nTác phẩm của ông luôn thể hiện phong cách độc đáo, tài hoa, sự hiểu biết phong phú nhiều mặt và vốn ngôn ngữ giàu có, điêu luyện.\r\nSau Cách mạng, Nguyễn Tuân trở thành một cây bút đặc sắc và tiêu biểu của nền văn học mới với nhiều tác phẩm ký, tùy bút và truyện ngắn. Ông từng giữ chức Tổng thư ký Hội Văn nghệ Việt Nam.\r\nNăm 1996 ông được truy tặng Giải thưởng Hồ Chí Minh về văn học nghệ thuật (đợt I).', 65000, 0.13, 100, 'NXB Kim Đồng', '22.5 x 14 x 1 cm', 192, 'Sách Văn Học', 'Văn Học', 'Bìa mềm', 'Nguyễn Tuân', 'Tiếng Việt', 2021),
    (10, 'Văn Học Trong Nhà Trường - Kịch Và Văn', '“Không bao giờ nhiều lời huênh hoang, anh [Nguyễn Huy Tưởng] đã hiểu sâu sắc cái sứ mệnh của người cầm bút, nó là cái nghề xây dựng tâm hồn nhưng cũng có thể phá phách tâm hồn.” - Nhà văn NGUYỄN ĐÌNH THI\r\n\r\n“Với Vũ Như Tô, Nguyễn Huy Tưởng là một nghệ sĩ tài năng, ông còn là một trí thức lớn, nghĩa là người mang tài năng, trí óc và tâm hồn mình cống hiến cho dân tộc và cho nhân loại.” - GS ĐỖ ĐỨC HIỂU\r\n\r\n“Các tác phẩm của Nguyễn Huy Tưởng, dù là tiểu thuyết, hay kịch, hay ký sự nữa, cũng đều gần chất sử thi.” - Nhà văn NHƯ PHONG\r\n\r\n“Sống mãi với thủ đôcó cái đường bệ, chín chắn của một tác phẩm vào cỡ lớn. Được dựng lên bằng một cây bút có nghề, có mực thước, có sự thận trọng, công phu tìm tòi suy nghĩ, có tấm lòng yêu dấu và chân thành của người viết.” - Nhà văn KIM LÂN\r\n\r\nNhằm giúp các bạn học sinh có một nền tảng kiến thức văn học phong phú, vững vàng, Nhà xuất bản Kim Đồng tổ chức biên soạn bộ sách Văn học trong nhà trường.\r\n\r\nBộ sách sẽ lần lượt giới thiệu tác phẩm của các tác giả thuộc nhiều trào lưu, thể loại, thời kì...\r\n\r\nNgoài giá trị tư liệu học tập, hi vọng bộ sách còn giúp bồi dưỡng thêm tình yêu văn học, khích lệ tư duy sáng tạo giúp người đọc có được cho mình những nhận định khách quan và hợp lí.', 50000, 0.18, 100, 'NXB Kim Đồng', '19 x 13 cm', 268, 'Sách Văn Học', 'Văn Học', 'Bìa mềm', 'Nguyễn Huy Tưởng', 'Tiếng Việt', 2020),
    (11, 'Văn Học Tuổi Hoa - Kẻ Trộm Bất Đắc Dĩ', 'Giả Hành Tôn, chú khỉ của Sơn, đột nhiên biến mất. Sơn cùng Tú và Trân lùng sục khắp nơi mà chú khỉ vẫn bặt vô âm tín. Trong thành phố lại xuất hiện những “ngôi nhà có ma” và những vụ mất trộm vô cùng kì lạ. Chuyện gì đã xảy ra vậy nhỉ?\r\n\r\nTác giả CHU QUANG MẠNH THẮNG\r\nSinh năm 1973, tại Bắc Giang. Nghề nghiệp: Viết văn, biên kịch, đạo diễn điện ảnh. Sinh sống tại TP. Hồ Chí Minh từ năm 1991.\r\n\r\nTruyện dài “Kẻ trộm bất đắc dĩ” đã được chuyển thể thành kịch bản phim điện ảnh mang tên “Truy tìm kẻ trộm” và lọt vào vòng Chung khảo Cuộc thi sáng tác kịch bản điện ảnh do Cục điện ảnh tổ chức năm 2020.', 40000, 0.23, 100, 'NXB Kim Đồng', '19 x 13 x 0.8 cm', 202, 'Sách Văn Học', 'Văn Học', 'Bìa mềm', 'Chu Quang Mạnh Thắng', 'Tiếng Việt', 2022),
    (12, 'Tự Nhiên Say - Văn Học Tuổi 20', 'Tập truyện xoay quanh cuộc sống của người dân quê, nơi đất vườn đã dần hóa thành đô thị làm thay đổi cả lòng người. Cách viết mộc mạc giản dị nhưng ẩn sau là những thông điệp sâu sắc, và bàng bạc một cái tình rất đỗi tha thiết với nhân gian.', 52000, 0.16, 100, 'NXB Trẻ', '19 x 13 x 0.8 cm', 202, 'Sách Văn Học', 'Văn Học', 'Bìa mềm', 'Phát Dương', 'Tiếng Việt', 2022);

    INSERT INTO `HINH_ANH` (`ID_HinhAnh`, `Anh`, `ID_SP`) VALUES
    (1, 'public/image/product/1/nha_bia.webp', 1),
    (2, 'public/image/product/2/van-hoc-tuoi-hoa_nhat_bia.webp', 2),
    (3, 'public/image/product/3/8934974187493.webp', 3),
    (4, 'public/image/product/4/8935236430852.webp', 4),
    (5, 'public/image/product/5/8935352607985.webp', 5),
    (7, 'public/image/product/2/2023_06_07_10_47_41_1-390x510.webp', 2),
    (8, 'public/image/product/2/2023_06_07_10_47_41_2-390x510.webp', 2),
    (9, 'public/image/product/2/2023_06_07_10_47_41_3-390x510.webp', 2),
    (10, 'public/image/product/1/2024_09_26_16_54_10_1-390x510.webp', 1),
    (11, 'public/image/product/1/2024_09_26_16_54_10_2-390x510.webp', 1),
    (12, 'public/image/product/1/2024_09_26_16_54_10_3-390x510.webp', 1),
    (13, 'public/image/product/3/2023_06_07_16_41_57_2-390x510.webp', 3),
    (14, 'public/image/product/3/2023_06_07_16_41_57_3-390x510.webp', 3),
    (15, 'public/image/product/3/2023_06_07_16_41_57_4-390x510.webp', 3),
    (16, 'public/image/product/4/2023_11_06_16_28_51_2-390x510.webp', 4),
    (17, 'public/image/product/4/2023_11_06_16_28_51_3-390x510.webp', 4),
    (18, 'public/image/product/4/2023_11_06_16_28_51_4-390x510.webp', 4),
    (19, 'public/image/product/6/8935212365857.webp', 6),
    (20, 'public/image/product/6/2023_12_12_15_33_45_1-390x510.webp', 6),
    (21, 'public/image/product/6/2023_12_12_15_33_45_2-390x510.webp', 6),
    (22, 'public/image/product/7/8935236425834.webp', 7),
    (23, 'public/image/product/7/2023_01_09_13_59_27_2-390x510.webp', 7),
    (24, 'public/image/product/7/2023_01_09_13_59_27_3-390x510.webp', 7),
    (25, 'public/image/product/7/2023_01_09_13_59_27_4-390x510.webp', 7),
    (26, 'public/image/product/8/image_195509_1_29510.webp', 8),
    (27, 'public/image/product/8/2021_06_19_10_47_06_2-390x510.webp', 8),
    (28, 'public/image/product/8/2021_06_19_10_47_06_3-390x510.webp', 8),
    (29, 'public/image/product/8/2021_06_19_10_47_06_4-390x510.webp', 8),
    (30, 'public/image/product/9/image_240031.webp', 9),
    (31, 'public/image/product/9/2021_12_09_16_08_26_2-390x510.webp', 9),
    (32, 'public/image/product/9/2021_12_09_16_08_26_3-390x510.webp', 9),
    (33, 'public/image/product/9/2021_12_09_16_08_26_4-390x510.webp', 9),
    (34, 'public/image/product/10/image_195509_1_38376.webp', 10),
    (35, 'public/image/product/11/8935244868012.webp', 11),
    (36, 'public/image/product/11/2022_11_09_13_50_54_2-390x510.webp', 11),
    (37, 'public/image/product/12/1733242691_674f2f435b3f9.jpg', 12),
    (38, 'public/image/product/12/1733242691_674f2f438f19d.jpg', 12);

    INSERT INTO SAN_PHAM_DE_XUAT (TenSP, NoiDung, TrangThai, GhiChu, UID, NgayYeuCau)
    VALUES
    ('Sản phẩm đề xuất 1', 'Đề xuất sản phẩm mới 1', "Đang chờ duyệt", 'Đang chờ duyệt', 1, '12-11-2024'),
    ('Sản phẩm đề xuất 2', 'Đề xuất sản phẩm mới 2', "Đã duyệt", 'Đã được duyệt', 2, '12-11-2024'),
    ('Sản phẩm đề xuất 3', 'Đề xuất sản phẩm mới 3', "Đã từ chối", 'Không được duyệt', 3, '12-11-2024'),
    ('Sản phẩm đề xuất 4', 'Đề xuất sản phẩm mới 4', "Đang chờ duyệt", 'Đang chờ duyệt', 6, '12-11-2024'),
    ('Sản phẩm đề xuất 5', 'Đề xuất sản phẩm mới 5', "Đã duyệt", 'Đã được duyệt', 7, '12-11-2024');

    INSERT INTO DANH_GIA (ID_SP, UID, NgayDanhGia, SoSao, NoiDung, TrangThai, PhanHoi)
    VALUES
    (1, 1, '20-10-2023', 5, 'Sản phẩm tuyệt vời!', 'Đang hiện', ''),
    (2, 2, '21-10-2023', 4, 'Rất hài lòng với sản phẩm này', 'Đang hiện', ''),
    (3, 3, '19-10-2023', 3, 'Sản phẩm tạm được, còn vài khuyết điểm', 'Đang hiện', ''),
    (4, 6, '18-10-2023', 2, 'Không tốt như mong đợi', 'Đang hiện', ''),
    (5, 7, '17-10-2023', 1, 'Sản phẩm rất tệ, không khuyến khích mua', 'Đang hiện', '');

    INSERT INTO BINH_LUAN (ID_SP, UID, NgayBinhLuan, NoiDung, PhanHoi, TrangThai)
    VALUES
    (1, 1, '21-10-2023', 'Sản phẩm rất tốt, tôi rất hài lòng.', '', 'Đang hiện'),
    (2, 2, '21-10-2023', 'Chất lượng ổn, giao hàng nhanh.', '', 'Đang hiện'),
    (3, 3, '20-10-2023', 'Giá hơi cao nhưng sản phẩm chất lượng.', '', 'Đang hiện'),
    (4, 6, '19-10-2023', 'Không như mong đợi, cần cải thiện.', '', 'Đang hiện'),
    (5, 7, '18-10-2023', 'Sản phẩm quá tệ, không đáng tiền.', '', 'Đang hiện');

    INSERT INTO MA_GIAM_GIA (Ma, TienGiam, DieuKien, SoLuong, TrangThai)
    VALUES
    ('GIAM10K', 10000.00, 'Female', 50, 'Kích hoạt'),
    ('GIAM50K', 50000.00, '> 200000', 50, 'Kích hoạt'),
    ('GIAM30%', 30.00, 'Chẵn', 50, 'Hết hạn'),
    ('GIAM50%', 50.00, '< 500000', 50, 'Kích hoạt'),
    ('GIAMCOD', 25000.00, 'COD', 50, 'Hết hạn'),
    ('FREESHIP', 0.00, 'Tất cả', 50, 'Kích hoạt');

    INSERT INTO DON_HANG (UID, NgayDat, TongTien, MaGiamGia, TrangThai, SDT, DiaChi, TenNguoiNhan, ThanhToan, PhuongThucThanhToan, HoaDon)
    VALUES
    (1, '20-10-2023', 300000.00, 'GIAM50K','Chờ xác nhận', 0123456789, 'TP.HCM', 'Trần Thành Tài','Chưa thanh toán', 'Bank', 250000.00),
    (2, '19-10-2023', 150000.00, 'GIAM50%','Chờ xác nhận', 0123456789, 'TP.HCM', 'Trần Thành Tài','Chưa thanh toán', 'COD', 75000.00),
    (3, '18-10-2023', 250000.00, 'GIAM10K','Chờ xác nhận', 0123456789, 'TP.HCM', 'Trần Thành Tài','Chưa thanh toán', 'Bank', 240000.00),
    (6, '17-10-2023', 100000.00, 'FREESHIP','Chờ xác nhận', 0123456789, 'TP.HCM', 'Trần Thành Tài','Chưa thanh toán', 'COD', 100000.00),
    (7, '16-10-2023', 200000.00, 'FREESHIP','Chờ xác nhận', 0123456789, 'TP.HCM', 'Trần Thành Tài','Chưa thanh toán', 'Bank', 200000.00);

    INSERT INTO GOM (ID_DonHang, ID_SP, SoLuong)
    VALUES
    (1, 1, 2),  -- Đơn hàng 1 mua 2 sản phẩm 1
    (1, 2, 1),  -- Đơn hàng 1 mua 1 sản phẩm 2
    (2, 3, 3),  -- Đơn hàng 2 mua 3 sản phẩm 3
    (3, 4, 1),  -- Đơn hàng 3 mua 1 sản phẩm 4
    (4, 5, 2),  -- Đơn hàng 4 mua 2 sản phẩm 5
    (5, 1, 1);  -- Đơn hàng 5 mua 1 sản phẩm 1

    INSERT INTO TRONG_GIO_HANG (UID, ID_SP, SoLuong)
    VALUES
    (1, 1, 2),  -- Khách hàng 1 có 2 sản phẩm 1 trong giỏ hàng
    (2, 2, 1),  -- Khách hàng 2 có 1 sản phẩm 2 trong giỏ hàng
    (3, 3, 3),  -- Khách hàng 3 có 3 sản phẩm 3 trong giỏ hàng
    (6, 4, 1),  -- Khách hàng 6 có 1 sản phẩm 4 trong giỏ hàng
    (7, 5, 2);  -- Khách hàng 7 có 2 sản phẩm 5 trong giỏ hàng

    INSERT INTO THICH (UID, ID_SP)
    VALUES
    (1, 1),  -- Khách hàng 1 thích sản phẩm 1
    (1, 2),  -- Khách hàng 1 thích sản phẩm 2
    (2, 3),  -- Khách hàng 2 thích sản phẩm 3
    (3, 4),  -- Khách hàng 3 thích sản phẩm 4
    (6, 5),  -- Khách hàng 6 thích sản phẩm 5
    (7, 1);  -- Khách hàng 7 thích sản phẩm 1

    INSERT INTO THONG_BAO (UID, NoiDung, TrangThai, Type, NgayThongBao)
    VALUES
    (1, 'Đơn hàng của bạn đã được xác nhận.', 'Unread', 'Đơn hàng', '10:00:00 21-10-2023'),
    (2, 'Sản phẩm yêu thích của bạn đã có hàng.', 'Read', 'Đơn hàng', '10:00:00 21-10-2023'),
    (3, 'Đơn hàng của bạn đang được vận chuyển.', 'Unread', 'Đơn hàng', '10:00:00 21-10-2023'),
    (6, 'Bạn đã nhận được một mã giảm giá mới.', 'Read', 'Đơn hàng', '10:00:00 21-10-2023'),
    (7, 'Sản phẩm trong giỏ hàng của bạn sắp hết.', 'Unread', 'Đơn hàng', '10:00:00 21-10-2023');

    INSERT INTO LOAI_THONG_BAO (MaThongBao, ID_DonHang, MaDeXuat)
    VALUES
    (1, 1, NULL),
    (2, 2, NULL),
    (3, 3, NULL),
    (4, 4, NULL),
    (5, 5, NULL);

    INSERT INTO `TIN_TUC` (`MaTinTuc`, `TieuDe`, `ThoiGianTao`, `NoiDung`, `TuKhoa`, `TrangThai`, `MoTa`) VALUES
    (1, 'Ra mắt Tủ sách Văn học trẻ', '10:00:00 21-10-2023', 'Nhằm trang bị cho các em học sinh tìm hiểu về nhiều lĩnh vực của cuộc sống, NXB Giáo dục Việt Nam vừa tổ chức lễ ra mắt Tủ sách Văn học trẻ. Tủ sách Văn học trẻ ra đời với mong muốn giới thiệu tới độc giả trẻ những tác phẩm văn học có giá trị nhân văn sâu sắc, phù hợp với tâm lý lứa tuổi bắt đầu tìm hiểu, nhận biết thế giới, những vấn đề xã hội, gia đình, nhà trường mà các em gặp phải nhưng nhiều khi không biết lối đi, không tìm được người chia sẻ; Đóng góp tích cực hơn nữa vào việc phát triển văn hoá đọc lành mạnh trong lứa tuổi học sinh, góp phần phát triển một thế hệ tương lai biết chia sẻ và có trách nhiệm. Nhân dịp này, ba trong số những cuốn sách đầu tiên trong Tủ sách văn học trẻ đã được ra mắt. Ba cuốn sách gồm “Estrella - Cô bé đến từ những vì sao”, “Estrella và Luz - Ngước mắt thấy mặt trăng”, “Kuyen - Chuyện của mặt trăng” thuộc chùm tác phẩm của nhà văn Roberto Fuente đề cập đến nhiều vấn đề khác nhau của cuộc sống từ góc nhìn chân thực của những nhân vật vô cùng đặc biệt. Đó là, tình bạn, tình cảm gia đình, khó khăn thường nhật, khác biệt về niềm tin, sự mất mát và chia ly, văn hoá bản địa… những thứ tưởng chừng nhạy cảm và khó nói lại trở nên thật nhẹ nhàng và dễ hiểu qua câu chuyện gần gũi của những đứa trẻ. Nhân vật xuyên suốt trong ba cuốn sách này là Estrella, một cô bé đặc biệt, sở hữu siêu năng lực, và đến từ một nơi nào đó ngoài trái đất. Cô bé ấy có cái nhìn khác biệt về thế giới, về xã hội của chúng ta. Thông qua trải nghiệm của Estrella bên những người bạn của mình, bằng giọng văn trong trẻo và tươi sáng, tác giả đã truyền tải những thông điệp đầy ý nghĩa đến độc giả ở mọi lứa tuổi, rằng chúng ta hãy dành thời gian để suy nghĩ về thế giới mà chúng ta đang sống, nhìn nhận lại những giá trị chúng ta đang theo đuổi, biết quan tâm, sẻ chia, lựa chọn cách sống tốt đẹp và hạnh phúc… Cũng trong khuôn khổ lễ ra mắt tủ sách đã diễn ra buổi toạ đàm với chủ đề “Khoảng trống về sách văn học cho thiếu nhi”. Trong buổi toạ đàm, các diễn giả đã chia sẻ những quan điểm về thị trường sách văn học dành cho lứa tuổi thiếu nhi cũng như những trăn trở của người làm sách cho thiếu nhi. Hiện nay, sách văn học dành cho thiếu nhi trên thị trường không nhiều, chủ yếu là những tác phẩm kinh điển, đã rất quen thuộc với nhiều thế hệ học sinh Việt Nam. Các tác phẩm mới được xuất bản trong thời gian gần đây chưa thực sự tạo được dấu ấn trên thị trường. Mặc dù không thể phủ nhận vai trò của sách văn học thiếu nhi trong việc bồi đắp trí tuệ, tình cảm, tư tưởng và đạo đức cho các độc giả nhỏ tuổi, nhưng trên thực tế, thể loại sách này chưa nhận được nhiều sự quan tâm từ phía các các nhà xuất bản cũng như các phụ huynh.', 'sách, giảm giá, khuyến mãi', 'Đang hiện', NULL),
    (2, 'Tác giả Nguyễn Chí Lợi và cuộc thử sức Hai trăm cây số phía Bắc Sài Gòn', '09:30:00 20-10-2023', 'Ước mơ câu chuyện dựng thành phim của đạo diễn Nguyễn Chí Lợi dang dở, buộc anh chuyển hướng sang viết sách để lưu lại những gì từng ấp ủ. Và thế là tác giả bước vào cuộc thử sức với... Hai trăm cây số phía Bắc Sài Gòn. Cuốn sách đầu tay Hai trăm cây số phía Bắc Sài Gòn của Nguyễn Chí Lợi do NXB Hội Nhà văn vừa ra mắt đã được độc giả đón nhận, thật sự là sự kiện đáng nhớ trong cuộc đời tác giả. Được biết, Nguyễn Chí Lợi sinh năm 1987, quê TP.Phan Thiết (Bình Thuận), anh vào TP.HCM học tập rồi sinh sống từ năm 2005 đến nay. Anh tâm sự: “Từ nhỏ tôi luôn quan tâm, yêu thích tìm hiểu về văn hóa và lịch sử của quê hương, đất nước mình. Lớn lên tôi luôn mong muốn mình được học hành chính quy với ngành đạo diễn phim nhằm kể lại bằng hình ảnh những câu chuyện có giá trị đã xảy ra suốt chiều dài lịch sử của nước Việt". Tuy nhiên, sau đó vì nhiều lý do khách quan cũng như chủ quan, Nguyễn Chí Lợi không thi đậu vào Trường ĐH Sân khấu - Điện ảnh TP.HCM như mong ước. Anh đành rẽ sang học một ngành khác và tốt nghiệp đại học năm 2009. Nhưng cơ duyên đưa đẩy anh lại được làm việc trong ngành phim ảnh và tổ chức sự kiện nên lại nuôi tiếp ước mơ làm phim luôn cháy bỏng năm nào. Anh quyết định theo học lớp đạo diễn dài hạn tại một trường điện ảnh tư nhân và đã tốt nghiệp. Kể về nội dung tác phẩm mới Hai trăm cây số phía Bắc Sài Gòn, Nguyễn Chí Lợi bộc bạch: “Tựa truyện như một tọa độ địa lý trên bản đồ, quả thật nó chính là câu thoại để trả lời cho câu hỏi quê hương bạn ở đâu của nữ nhân vật chính trong truyện. Hai trăm cây số phía bắc Sài Gòn - đó là Phan Thiết, bối cảnh chính diễn ra những câu chuyện liên quan đến đôi nhân vật chính đi xuyên suốt chiều dài hơn trăm năm lịch sử của thành phố".', 'sách mới, tác giả nổi tiếng', 'Đang hiện', NULL);

    INSERT INTO `ANH_MINH_HOA` (`MaAnh`, `MoTa`, `LinkAnh`, `MaTinTuc`) VALUES
    (1, '', '/public/image/new/1/1733318836_675058b4d90c9.jpg', 1),
    (2, '', '/public/image/new/2/1733318637_675057ed1dcf4.jpg', 2);

    INSERT INTO `BANNER` (`MaBanner`, `Image`, `IdSP`, `MoTa`, `TrangThai`) VALUES
    (1, '/public/image/1.webp', 1, '', 'Đang hiện'),
    (2, '/public/image/2.webp', 2, '', 'Đang hiện'),
    (3, '/public/image/3.webp', 3, '', 'Đang hiện');

    INSERT INTO DOI_TAC (Ten, HinhAnh, LienKet, `TrangThai`)
    VALUES
    ('VNPOST', '/public/image/vnpost1.png', 'https://vnpost.vn/', 'Đang hiện'),
    ('Ninja Van', '/public/image/Logo_ninjavan.webp', 'https://www.ninjavan.co/vi-vn', 'Đang hiện'),
    ('Ahamove', '/public/image/ahamove_logo3.webp', 'https://www.ahamove.com/', 'Đang hiện'),
    ('Snappy', '/public/image/icon_snappy1.webp', 'https://snappy.vn/', 'Đang hiện'),
    ('Pay Os', '/public/image/payos.svg', 'https://payos.vn/', 'Đang hiện');

    INSERT INTO HE_THONG (MaHeThong, TrangThaiBaoTri, TuKhoa, ClientID, APIKey, Checksum)
    VALUES
    (1, FALSE, '', '', '', ''); 

    INSERT INTO THONG_TIN_LIEN_HE (Loai, ThongTin, HinhAnh, TrangThai)
    VALUES
    ('Email', 'contact@website.com', '/public/image/email.png', 'Đang hiện'),
    ('Tư vấn mua hàng', '+84 123 456 789', '/public/image/consultation.jpg', 'Đang hiện'),
    ('Tư vấn đổi sách', '+84 123 456 745', '/public/image/warranty.jfif', 'Đang hiện'),
    ('Khiếu nại', '+84 123 456 123', '/public/image/complaint.jfif', 'Đang hiện');


    INSERT INTO MANG_XA_HOI (HinhAnh, LienKet, TrangThai)
    VALUES
    ('/public/image/facebook.png', 'https://www.facebook.com', 'Đang hiện'),
    ('/public/image/instagram.png', 'https://www.instagram.com/website', 'Đang hiện'),
    ('/public/image/youtube.png', 'https://www.youtube.com', 'Đang hiện'),
    ('/public/image/telegram.png', 'https://web.telegram.org/', 'Đang hiện');
