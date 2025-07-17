<!-- # Test Promotion Usage Tracking System

## Mô tả hệ thống
Hệ thống tracking sử dụng mã giảm giá đã được implement hoàn chỉnh với các tính năng:

1. **Bảng tracking**: `user_promotion_usage` lưu thông tin sử dụng mã giảm giá
2. **Model**: `UserPromotionUsage` với các phương thức helper
3. **Validation**: Kiểm tra giới hạn sử dụng trong controller
4. **API filtering**: Loại bỏ mã đã hết lượt từ danh sách khả dụng

## Các thành phần đã tạo/cập nhật

### 1. Migration
- File: `database/migrations/2025_07_17_110401_create_user_promotion_usage_table.php`
- Tạo bảng tracking với các trường: user_id, promotion_id, booking_id, usage_count, timestamps

### 2. Model UserPromotionUsage
- File: `app/Models/UserPromotionUsage.php`
- Phương thức `hasUserUsedPromotion()`: Kiểm tra user đã dùng mã chưa
- Phương thức `getUserPromotionUsageCount()`: Đếm số lần đã sử dụng
- Phương thức `recordUsage()`: Ghi lại việc sử dụng mã

### 3. Cập nhật Model Promotion
- File: `app/Models/Promotion.php`
- Thêm relationship `userUsages()`
- Thêm phương thức `canUserUse()` kiểm tra khả năng sử dụng

### 4. Cập nhật HomeController
- File: `app/Http/Controllers/Client/HomeController.php`
- `applyDiscountCode()`: Thêm validation và tracking
- `applyDiscountCodeAutomatically()`: Thêm validation và tracking
- `getAvailablePromotions()`: Lọc bỏ mã đã hết lượt
- `getUserRankPromotions()`: Thêm filter usage limit
- `getGeneralPromotions()`: Thêm filter usage limit  
- `getOtherRankPromotions()`: Thêm filter usage limit

## Cách thức hoạt động

### Khi user áp dụng mã giảm giá:
1. Kiểm tra user đã đăng nhập
2. Validate mã tồn tại và còn hiệu lực
3. **KIỂM TRA MỚI**: Kiểm tra user còn lượt sử dụng mã này không
4. Áp dụng giảm giá nếu hợp lệ
5. **GHI LẠI**: Tăng usage_count trong bảng user_promotion_usage

### Khi hiển thị danh sách mã khả dụng:
1. Lấy tất cả mã phù hợp (theo rank, chung, rank cao hơn)
2. **LỌC MỚI**: Loại bỏ những mã mà user đã sử dụng hết lượt
3. Trả về danh sách đã được lọc

## Database Query Logic

### Lọc mã đã hết lượt:
```sql
WHERE NOT EXISTS (
    SELECT 1 FROM user_promotion_usage upu 
    WHERE upu.promotion_id = promotions.id 
    AND upu.user_id = ? 
    AND upu.usage_count >= promotions.usage_limit_per_user
    AND promotions.usage_limit_per_user IS NOT NULL
)
```

## Test Cases cần kiểm tra

### 1. Test cơ bản:
- User lần đầu sử dụng mã: Thành công
- User sử dụng mã lần 2 (nếu limit > 1): Thành công
- User sử dụng mã vượt quá limit: Thất bại

### 2. Test API getAvailablePromotions:
- Mã chưa sử dụng: Xuất hiện trong danh sách
- Mã đã hết lượt: Không xuất hiện trong danh sách
- Mã không có limit: Luôn xuất hiện

### 3. Test với nhiều user:
- User A sử dụng hết limit không ảnh hưởng User B
- Mỗi user có tracking riêng biệt

## Status: ✅ COMPLETED
- [x] Migration tạo bảng tracking
- [x] Model UserPromotionUsage
- [x] Cập nhật Model Promotion
- [x] Validation trong applyDiscountCode
- [x] Validation trong applyDiscountCodeAutomatically  
- [x] Filter trong getAvailablePromotions
- [x] Test và debug -->
