package com.example.wtcspaddock.api;

public class Constants {
    // TU DOMINIO DUCKDNS
    public static final String SERVER_IP = "wtcs-paddock.duckdns.org";

    // NO USAMOS PUERTO (Es el 80 por defecto)
    // Eliminamos la variable PORT para no liarnos o la dejamos vacía pero no la usamos abajo
    public static final String PORT = "";

    // URL CORRECTA (Sin los dos puntos ':')
    public static final String BASE_URL = "http://" + SERVER_IP + "/api/";

    // --- MÉTODO PARA ARREGLAR IMÁGENES ---
    public static String fixImageUrl(String url) {
        if (url == null) return null;

        String fixedUrl = url;

        // 1. Reemplazar localhost/127.0.0.1 por tu dominio
        if (fixedUrl.contains("localhost")) {
            fixedUrl = fixedUrl.replace("localhost", SERVER_IP);
        } else if (fixedUrl.contains("127.0.0.1")) {
            fixedUrl = fixedUrl.replace("127.0.0.1", SERVER_IP);
        }

        // 2. Limpiar puertos viejos si quedan (por si la BD tiene guardado :8000)
        if (fixedUrl.contains(":8000")) {
            fixedUrl = fixedUrl.replace(":8000", "");
        }

        // 3. Rutas relativas
        if (!fixedUrl.startsWith("http")) {
            fixedUrl = "http://" + SERVER_IP + "/storage/" + fixedUrl;
        }

        return fixedUrl;
    }
}