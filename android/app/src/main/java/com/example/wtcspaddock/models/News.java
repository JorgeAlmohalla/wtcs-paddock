package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;

public class News {
    private int id;
    private String title;
    private String date;

    @SerializedName("image") // Tu JSON dice "image"
    private String imageUrl;

    private String excerpt; // Resumen
    private String content; // HTML completo (para el detalle)

    // Getters
    public int getId() { return id; }
    public String getTitle() { return title; }
    public String getDate() { return date; }
    public String getExcerpt() { return excerpt; }
    public String getContent() { return content; }

    public String getImageUrl() {
        return com.example.wtcspaddock.api.Constants.fixImageUrl(imageUrl);
    }
}