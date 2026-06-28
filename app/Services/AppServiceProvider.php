use App\Services\DndApiService;

public function register(): void
{
    $app->singleton(DndApiService::class, function () {
        return new DndApiService();
    });
}