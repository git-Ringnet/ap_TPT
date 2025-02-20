<?php

namespace App\Models;

use App\Helpers\GlobalHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Imports extends Model
{
    use HasFactory;
    protected $table = 'imports';

    protected $fillable = [
        'import_code',
        'user_id',
        'phone',
        'date_create',
        'provider_id',
        'contact_person',
        'address',
        'note',
        'warehouse_id',
    ];
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider()
    {
        return $this->belongsTo(Providers::class, 'provider_id');
    }

    public function productImports()
    {
        return $this->hasMany(ProductImport::class, 'import_id', 'id');
    }

    public function getAllImports()
    {
        $warehouse_id = GlobalHelper::getWarehouseId();
        $imports = Imports::leftJoin("providers", "providers.id", "imports.provider_id")
            ->leftJoin("users", "users.id", "imports.user_id")
            ->select("providers.provider_name", "users.name", "imports.*");
        if ($warehouse_id) {
            $imports = $imports->where('imports.warehouse_id', $warehouse_id);
        }
        return $imports->get();
    }

    public function addImport($data)
    {
        $warehouse_id = GlobalHelper::getWarehouseId();
        $arrImport = [
            'import_code' => $data['import_code'],
            'user_id' => $data['user_id'],
            'phone' => $data['phone'],
            'date_create' => $data['date_create'],
            'provider_id' => $data['provider_id'],
            'contact_person' => $data['contact_person'],
            'address' => $data['address'],
            'note' => $data['note'],
            'created_at' => now(),
            'warehouse_id' => $warehouse_id ?? 1,
        ];
        $import = DB::table($this->table)->insertGetId($arrImport);
        return $import;
    }
    public static function generateImportCode()
    {
        $prefix = 'PNH';

        // Lấy mã lớn nhất hiện tại theo prefix
        $lastCode = DB::table('imports')
            ->where('import_code', 'like', "{$prefix}%")
            ->orderBy('import_code', 'desc')
            ->value('import_code');

        // Tách số thứ tự nếu mã cuối cùng tồn tại
        $newNumber = 1; // Mặc định số thứ tự là 1
        if ($lastCode) {
            $lastNumber = (int) substr($lastCode, strlen($prefix)); // Lấy phần số sau prefix
            $newNumber  = $lastNumber + 1;
        }

        // Định dạng số thứ tự thành chuỗi 5 chữ số (001, 002, ...)
        $formattedNumber = str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        // Kết hợp thành mã mới
        return "{$prefix}{$formattedNumber}";
    }
    public function getImportAjax($data = null)
    {
        // Lấy dữ liệu Imports với quan hệ
        $imports = Imports::with(['user', 'provider'])
            ->select(
                'imports.*',
                'users.name as username',
                'providers.provider_name as provide_name'
            )
            ->join('users', 'imports.user_id', '=', 'users.id') // Join với bảng users
            ->join(
                'providers',
                'imports.provider_id',
                '=',
                'providers.id'
            );
        if (!empty($data)) {
            if (!empty($data['search'])) {
                $imports->where(function ($query) use ($data) {
                    $query->where('import_code', 'like', '%' . $data['search'] . '%')
                        ->orWhere('imports.note', 'like', '%' . $data['search'] . '%');
                });
            }
            if (!empty($data['ma'])) {
                $imports->where('import_code', 'like', '%' . $data['ma'] . '%');
            }
            if (!empty($data['note'])) {
                $imports->where('imports.note', 'like', '%' . $data['note'] . '%');
            }
            if (!empty($data['date'][0]) && !empty($data['date'][1])) {
                $dateStart = Carbon::parse($data['date'][0]);
                $dateEnd = Carbon::parse($data['date'][1])->endOfDay();
                $imports->whereBetween('date_create', [$dateStart, $dateEnd]);
            }
            if (!empty($data['provider'])) {
                $imports->whereHas('provider', function ($query) use ($data) {
                    $query->whereIn('id', $data['provider']);
                });
            }
            if (!empty($data['user'])) {
                $imports->whereHas('user', function ($query) use ($data) {
                    $query->whereIn('id', $data['user']);
                });
            }
        }
        if (isset($data['sort']) && isset($data['sort'][0])) {
            $imports = $imports->orderBy($data['sort'][0], $data['sort'][1]);
        }
        return $imports->get();
    }
}
