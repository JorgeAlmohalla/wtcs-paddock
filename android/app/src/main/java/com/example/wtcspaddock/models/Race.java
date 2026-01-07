package com.example.wtcspaddock.models;
import com.google.gson.annotations.SerializedName;

public class Race {
    private int id;
    private String title;

    @SerializedName("track_name")
    private String trackName;

    // Tu fecha incluye hora: "2025-10-11 17:00:00"
    private String date;

    // CORRECCIÓN: En tu JSON la clave es "image", no "image_url"
    @SerializedName("image")
    private String imageUrl;

    public String getTitle() { return title; }
    public String getTrackName() { return trackName; }
    public String getDate() { return date; }

    // TRUCO EXPERTO: Parchear URL para Emulador
    public String getImageUrl() {
        if (imageUrl != null && imageUrl.contains("127.0.0.1")) {
            return imageUrl.replace("127.0.0.1", "10.0.2.2");
        }
        return imageUrl;
    }
}