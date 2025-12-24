<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    /**
     * API Base URL - Có thể cấu hình trong .env
     */
    private function getApiBaseUrl()
    {
        return config('services.provies.api_url', 'https://provinces.open-api.vn/api');
    }

    /**
     * Get all provinces/cities from Provies API
     */
    public function getProvinces()
    {
        try {
            // Cache provinces for 24 hours
            $provinces = Cache::remember('provinces', 60 * 24, function () {
                $response = Http::timeout(10)->get($this->getApiBaseUrl() . '/p/');
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Transform data to match our format
                    return collect($data)->map(function ($item) {
                        return [
                            'id' => $item['code'],
                            'name' => $item['name'],
                            'code' => $item['code'],
                        ];
                    })->sortBy('name')->values()->toArray();
                }
                
                // Fallback to default data if API fails
                return $this->getDefaultProvinces();
            });

            return response()->json($provinces);
        } catch (\Exception $e) {
            Log::error('Error fetching provinces: ' . $e->getMessage());
            
            // Return default data on error
            return response()->json($this->getDefaultProvinces());
        }
    }

    /**
     * Get districts by province code from Provies API
     */
    public function getDistricts($provinceId)
    {
        try {
            $cacheKey = "districts_{$provinceId}";
            
            $districts = Cache::remember($cacheKey, 60 * 24, function () use ($provinceId) {
                $response = Http::timeout(10)->get($this->getApiBaseUrl() . "/p/{$provinceId}?depth=2");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['districts'])) {
                        return collect($data['districts'])->map(function ($item) use ($provinceId) {
                            return [
                                'id' => $item['code'],
                                'name' => $item['name'],
                                'province_id' => $provinceId,
                            ];
                        })->sortBy('name')->values()->toArray();
                    }
                }
                
                // Fallback to default data
                return $this->getDefaultDistricts($provinceId);
            });

            return response()->json($districts);
        } catch (\Exception $e) {
            Log::error("Error fetching districts for province {$provinceId}: " . $e->getMessage());
            
            // Return default data on error
            return response()->json($this->getDefaultDistricts($provinceId));
        }
    }

    /**
     * Get wards by district code from Provies API
     */
    public function getWards($districtId)
    {
        try {
            $cacheKey = "wards_{$districtId}";
            
            $wards = Cache::remember($cacheKey, 60 * 24, function () use ($districtId) {
                $response = Http::timeout(10)->get($this->getApiBaseUrl() . "/d/{$districtId}?depth=2");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['wards'])) {
                        return collect($data['wards'])->map(function ($item) use ($districtId) {
                            return [
                                'id' => $item['code'],
                                'name' => $item['name'],
                                'district_id' => $districtId,
                            ];
                        })->sortBy('name')->values()->toArray();
                    }
                }
                
                // Fallback to default data
                return $this->getDefaultWards($districtId);
            });

            return response()->json($wards);
        } catch (\Exception $e) {
            Log::error("Error fetching wards for district {$districtId}: " . $e->getMessage());
            
            // Return default data on error
            return response()->json($this->getDefaultWards($districtId));
        }
    }

    /**
     * Default provinces data (fallback)
     */
    private function getDefaultProvinces()
    {
        return [
            ['id' => 1, 'name' => 'Hà Nội', 'code' => '1'],
            ['id' => 79, 'name' => 'Hồ Chí Minh', 'code' => '79'],
            ['id' => 48, 'name' => 'Đà Nẵng', 'code' => '48'],
            ['id' => 31, 'name' => 'Hải Phòng', 'code' => '31'],
            ['id' => 89, 'name' => 'An Giang', 'code' => '89'],
            ['id' => 77, 'name' => 'Bà Rịa - Vũng Tàu', 'code' => '77'],
            ['id' => 24, 'name' => 'Bắc Giang', 'code' => '24'],
            ['id' => 6, 'name' => 'Bắc Kạn', 'code' => '6'],
            ['id' => 95, 'name' => 'Bạc Liêu', 'code' => '95'],
            ['id' => 27, 'name' => 'Bắc Ninh', 'code' => '27'],
            ['id' => 83, 'name' => 'Bến Tre', 'code' => '83'],
            ['id' => 52, 'name' => 'Bình Định', 'code' => '52'],
            ['id' => 74, 'name' => 'Bình Dương', 'code' => '74'],
            ['id' => 70, 'name' => 'Bình Phước', 'code' => '70'],
            ['id' => 60, 'name' => 'Bình Thuận', 'code' => '60'],
            ['id' => 96, 'name' => 'Cà Mau', 'code' => '96'],
            ['id' => 4, 'name' => 'Cao Bằng', 'code' => '4'],
            ['id' => 66, 'name' => 'Đắk Lắk', 'code' => '66'],
            ['id' => 67, 'name' => 'Đắk Nông', 'code' => '67'],
            ['id' => 11, 'name' => 'Điện Biên', 'code' => '11'],
            ['id' => 75, 'name' => 'Đồng Nai', 'code' => '75'],
            ['id' => 87, 'name' => 'Đồng Tháp', 'code' => '87'],
            ['id' => 64, 'name' => 'Gia Lai', 'code' => '64'],
            ['id' => 2, 'name' => 'Hà Giang', 'code' => '2'],
            ['id' => 35, 'name' => 'Hà Nam', 'code' => '35'],
            ['id' => 42, 'name' => 'Hà Tĩnh', 'code' => '42'],
            ['id' => 30, 'name' => 'Hải Dương', 'code' => '30'],
            ['id' => 93, 'name' => 'Hậu Giang', 'code' => '93'],
            ['id' => 17, 'name' => 'Hòa Bình', 'code' => '17'],
            ['id' => 33, 'name' => 'Hưng Yên', 'code' => '33'],
            ['id' => 56, 'name' => 'Khánh Hòa', 'code' => '56'],
            ['id' => 91, 'name' => 'Kiên Giang', 'code' => '91'],
            ['id' => 62, 'name' => 'Kon Tum', 'code' => '62'],
            ['id' => 12, 'name' => 'Lai Châu', 'code' => '12'],
            ['id' => 68, 'name' => 'Lâm Đồng', 'code' => '68'],
            ['id' => 20, 'name' => 'Lạng Sơn', 'code' => '20'],
            ['id' => 10, 'name' => 'Lào Cai', 'code' => '10'],
            ['id' => 80, 'name' => 'Long An', 'code' => '80'],
            ['id' => 36, 'name' => 'Nam Định', 'code' => '36'],
            ['id' => 40, 'name' => 'Nghệ An', 'code' => '40'],
            ['id' => 37, 'name' => 'Ninh Bình', 'code' => '37'],
            ['id' => 58, 'name' => 'Ninh Thuận', 'code' => '58'],
            ['id' => 25, 'name' => 'Phú Thọ', 'code' => '25'],
            ['id' => 54, 'name' => 'Phú Yên', 'code' => '54'],
            ['id' => 44, 'name' => 'Quảng Bình', 'code' => '44'],
            ['id' => 49, 'name' => 'Quảng Nam', 'code' => '49'],
            ['id' => 51, 'name' => 'Quảng Ngãi', 'code' => '51'],
            ['id' => 22, 'name' => 'Quảng Ninh', 'code' => '22'],
            ['id' => 45, 'name' => 'Quảng Trị', 'code' => '45'],
            ['id' => 94, 'name' => 'Sóc Trăng', 'code' => '94'],
            ['id' => 14, 'name' => 'Sơn La', 'code' => '14'],
            ['id' => 72, 'name' => 'Tây Ninh', 'code' => '72'],
            ['id' => 34, 'name' => 'Thái Bình', 'code' => '34'],
            ['id' => 19, 'name' => 'Thái Nguyên', 'code' => '19'],
            ['id' => 38, 'name' => 'Thanh Hóa', 'code' => '38'],
            ['id' => 46, 'name' => 'Thừa Thiên Huế', 'code' => '46'],
            ['id' => 82, 'name' => 'Tiền Giang', 'code' => '82'],
            ['id' => 84, 'name' => 'Trà Vinh', 'code' => '84'],
            ['id' => 8, 'name' => 'Tuyên Quang', 'code' => '8'],
            ['id' => 86, 'name' => 'Vĩnh Long', 'code' => '86'],
            ['id' => 26, 'name' => 'Vĩnh Phúc', 'code' => '26'],
            ['id' => 15, 'name' => 'Yên Bái', 'code' => '15'],
        ];
    }

    /**
     * Default districts data (fallback)
     */
    private function getDefaultDistricts($provinceId)
    {
        // Return empty array if no default data
        // API should handle this
        return [];
    }

    /**
     * Default wards data (fallback)
     */
    private function getDefaultWards($districtId)
    {
        // Return empty array if no default data
        // API should handle this
        return [];
    }
}
