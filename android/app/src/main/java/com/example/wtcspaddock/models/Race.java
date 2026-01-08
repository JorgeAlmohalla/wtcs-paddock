package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName; // <--- IMPRESCINDIBLE

public class Race {
    private int id;

    private int round; // Coincide con JSON "round"

    @SerializedName("track_name") // <--- ESTO ES LA CLAVE. Sin esto, no lee el nombre.
    private String trackName;

    private String date; // Coincide con JSON "date"

    @SerializedName("image") // Coincide con JSON "image" (antes era image_url, ojo con esto)
    private String imageUrl;

    private String title;
    private String winner;

    // Getters
    public int getRound() { return round; }

    public String getTrackName() {
        // Protección anti-nulos para que no salga vacío
        return trackName != null ? trackName : "Unknown Track";
    }

    public String getDate() { return date; }

    public String getImageUrl() {
        // Parche para localhost (Emulador)
        if (imageUrl != null && imageUrl.contains("127.0.0.1")) {
            return imageUrl.replace("127.0.0.1", "10.0.2.2");
        }
        return imageUrl;
    }

    public String getTitle() { return title; }
    public String getWinner() { return winner; }
}