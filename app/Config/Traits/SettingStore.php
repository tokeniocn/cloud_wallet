<?php

namespace App\Config\Traits;

use App\Models\Setting;
use Illuminate\Filesystem\Filesystem;

trait SettingStore
{
    /**
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return Setting::class;
    }

    public function loadSettingsFromCachedFile()
    {
        $path = $this->getSettingsCachedPath();

        if (file_exists($path)) {
            $this->set(require $path);
        }
    }

    public function getSettingsCachedPath()
    {
        return storage_path('framework/settings.php');
    }

    public function cacheSettingsToFile()
    {
        $modelClass = $this->getModel();

        $items = $modelClass::all()
            ->map(function ($setting) {
                return [
                    'key'   => (! empty($setting->module) ? $setting->module . '::' : '') . $setting->key,
                    'value' => $setting->value,
                ];
            })
            ->keyBy('key')
            ->map(function ($setting) {
                return $setting['value'];
            })
            ->toArray();

        $path = $this->getSettingsCachedPath();

        app(Filesystem::class)
            ->put($path, '<?php return ' . var_export($items, true) . ';' . PHP_EOL);
    }

    public function store($key, $value = null, $refreshCache = true)
    {
        if (is_array($key)) {
            $keys = $key;

            if ($value != null) {
                $refreshCache = boolval($value);
            }
        } else {
            $keys = [$key => $value];
        }

        $modelClass = $this->getModel();

        foreach ($keys as $key => $value) {
            list($module, $key) = explode('::', $key);

            $model = $modelClass::firstOrNew([
                'key' => $key,
                'module' => $module,
            ]);
            $model->value = $value;
            $model->saveOrFail();
        }

        $this->set($keys);

        if ($refreshCache) {
            $this->cacheSettingsToFile();
        }
    }
}
