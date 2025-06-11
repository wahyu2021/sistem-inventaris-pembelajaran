<?php

namespace App\Services;

use App\Models\Location;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocationService
{
    /**
     * Membuat lokasi baru.
     *
     * @param array $data Data dari LocationForm.
     * @param ?UploadedFile $image File gambar yang diunggah.
     * @return Location
     */
    public function createLocation(array $data, ?UploadedFile $image): Location
    {
        if ($image) {
            $data['image'] = $image->store('location-images', 'public');
        }

        return Location::create($data);
    }

    /**
     * Memperbarui lokasi yang ada.
     *
     * @param Location $location Model yang akan diperbarui.
     * @param array $data Data dari LocationForm.
     * @param ?UploadedFile $newImage File gambar baru (jika ada).
     * @return Location
     */
    public function updateLocation(Location $location, array $data, ?UploadedFile $newImage): Location
    {
        // Jika ada gambar baru yang diunggah
        if ($newImage) {
            // Hapus gambar lama jika ada
            if ($location->image) {
                Storage::disk('public')->delete($location->image);
            }
            // Simpan gambar baru dan tambahkan path-nya ke data
            $data['image'] = $newImage->store('location-images', 'public');
        }

        $location->update($data);

        return $location;
    }

    /**
     * Menghapus lokasi beserta gambarnya.
     *
     * @param Location $location Model yang akan dihapus.
     */
    public function deleteLocation(Location $location): void
    {
        // Hapus gambar dari storage jika ada
        if ($location->image) {
            Storage::disk('public')->delete($location->image);
        }

        $location->delete();
    }
}
