package com.example.wtcspaddock.models;
import com.google.gson.annotations.SerializedName;

public class Race {
    private int id;
    private String title;

    @SerializedName("track_name")
    private String trackName;

    private String date;
    private String status;

    @SerializedName("image_url")
    private String imageUrl;

    // Genera Getters
    public String getTitle() { return title; }
    public String getTrackName() { return trackName; }
    public String getDate() { return date; }
    public String getImageUrl() { return imageUrl; }
}