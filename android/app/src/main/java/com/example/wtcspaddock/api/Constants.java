package com.example.wtcspaddock.api;

public class Constants {
    // CAMBIA ESTO POR TU IP ACTUAL (La que te diga ipconfig)
    // Funciona tanto en Emulador como en Móvil Real
    public static final String CURRENT_IP = "192.168.0.13";

    public static final String PORT = "8000";
    public static final String BASE_URL = "http://" + CURRENT_IP + ":" + PORT + "/api/";

    // --- MÉTODO MÁGICO PARA ARREGLAR IMÁGENES ---
    // Todos los modelos llamarán a esto. Si la URL viene mal, esto la arregla.
    public static String fixImageUrl(String url) {
        if (url == null) return null;

        // Si la URL ya viene perfecta con la IP actual, no tocamos nada
        if (url.contains(CURRENT_IP)) return url;

        // Si viene con localhost, 127.0.0.1 o es relativa, la forzamos a tu IP
        String fixedUrl = url;

        if (url.contains("localhost")) {
            fixedUrl = url.replace("localhost", CURRENT_IP);
        } else if (url.contains("127.0.0.1")) {
            fixedUrl = url.replace("127.0.0.1", CURRENT_IP);
        } else if (!url.startsWith("http")) {
            // Si viene relativa ("avatars/foto.jpg"), le pegamos el dominio
            fixedUrl = "http://" + CURRENT_IP + ":" + PORT + "/storage/" + url;
        }

        return fixedUrl;
    }
}