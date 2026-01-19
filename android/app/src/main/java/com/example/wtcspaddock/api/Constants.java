package com.example.wtcspaddock.api;

public class Constants {
    // CAMBIA ESTO por tu IP real si cambia (míralo en ipconfig)
    public static final String SERVER_IP = "192.168.0.13";

    // Dejamos el puerto vacío porque Apache usa el 80 por defecto
    public static final String PORT = "";

    // URL Base para la API (http://192.168.0.13/api/)
    public static final String BASE_URL = "http://" + SERVER_IP + "/api/";

    // --- MÉTODO PARA ARREGLAR IMÁGENES ---
    public static String fixImageUrl(String url) {
        if (url == null) return null;

        String fixedUrl = url;

        // 1. Reemplazar localhost/127.0.0.1 por la IP real
        if (fixedUrl.contains("localhost")) {
            fixedUrl = fixedUrl.replace("localhost", SERVER_IP);
        } else if (fixedUrl.contains("127.0.0.1")) {
            fixedUrl = fixedUrl.replace("127.0.0.1", SERVER_IP);
        }

        // 2. ELIMINAR EL PUERTO 8000 (Vital si vienes de artisan serve)
        // Como ahora usamos Apache (puerto 80), el :8000 sobra y rompería la imagen
        if (fixedUrl.contains(":8000")) {
            fixedUrl = fixedUrl.replace(":8000", "");
        }

        // 3. Si viene relativa (sin http), le pegamos la base del storage
        if (!fixedUrl.startsWith("http")) {
            // Asumiendo que tu Apache apunta directamente a la carpeta public
            fixedUrl = "http://" + SERVER_IP + "/storage/" + fixedUrl;
        }

        return fixedUrl;
    }
}