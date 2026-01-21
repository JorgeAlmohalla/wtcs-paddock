<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;

trait CompressUploads
{
    protected static function bootCompressUploads()
    {
        static::saved(function ($model) {
            
            foreach ($model->compressImageFields ?? [] as $field) {
                
                // Si el campo tiene valor (hay una imagen)
                if (!empty($model->$field)) {
                    
                    $relativePath = $model->$field;
                    $fullPath = Storage::disk('public')->path($relativePath);
                    
                    // Verificamos si el archivo existe
                    if (file_exists($fullPath)) {
                        
                        // --- AQUÍ ESTÁ LA MAGIA ---
                        // En lugar de preguntar al modelo, preguntamos al archivo.
                        // ¿Pesa más de 300KB (307200 bytes)?
                        $fileSize = filesize($fullPath);
                        
                        if ($fileSize > 307200) { 
                            Log::info("⚖️ IMAGEN PESADA DETECTADA ($field): " . round($fileSize / 1024) . "KB. Comprimiendo...");

                            try {
                                // 1. Cargar
                                $image = Image::read($fullPath);
                                
                                // 2. Redimensionar si es gigante (ancho > 1200px)
                                if ($image->width() > 800) {
                                    $image->scaleDown(width: 800);
                                }

                                // 3. CONVERSIÓN FORZADA A JPG
                                // Esto es lo que faltaba. Convertimos a JPG con calidad 60.
                                // Esto destroza el tamaño de archivo (para bien) aunque sea un PNG.
                                $encoded = $image->toJpeg(60);
                                
                                // 4. Guardar sobre el mismo archivo
                                $encoded->save($fullPath);

                                
                                // Limpiar caché de estadísticas de archivo de PHP
                                clearstatcache();
                                $newSize = filesize($fullPath);
                                Log::info("✅ COMPRESIÓN ÉXITO (JPG FORCE). Nuevo tamaño: " . round($newSize / 1024) . "KB");
                                
                            } catch (\Exception $e) {
                                Log::error("❌ ERROR AL COMPRIMIR: " . $e->getMessage());
                            }
                        } else {
                            Log::info("👍 La imagen $field ya es ligera (" . round($fileSize / 1024) . "KB). No se toca.");
                        }
                    }
                }
            }
        });
    }
}