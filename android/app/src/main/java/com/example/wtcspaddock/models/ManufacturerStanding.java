package com.example.wtcspaddock.models;
import com.google.gson.annotations.SerializedName;

public class ManufacturerStanding {
    private String name;
    private int points;
    @SerializedName("team_count")
    private int teamCount;
    private String color;

    // Getters
    public String getName() { return name; }
    public int getPoints() { return points; }
    public int getTeamCount() { return teamCount; }
    public String getColor() { return color; }
}