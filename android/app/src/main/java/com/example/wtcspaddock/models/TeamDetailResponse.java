package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class TeamDetailResponse {
    private int id;
    private String name;
    private String color;
    @SerializedName("car_model") private String carModel;
    private String type; // works/privateer
    @SerializedName("livery_image") private String liveryImage;
    private String bio;
    private String logo;

    private TeamStats stats;
    private TeamSpecs specs;
    private List<RosterMember> roster;

    // Getters
    public int getId() { return id; }
    public String getName() { return name; }
    public String getCarModel() { return carModel; }
    public TeamStats getStats() { return stats; }
    public TeamSpecs getSpecs() { return specs; }
    public List<RosterMember> getRoster() { return roster; }
    public String getBio() { return bio; }

    public String getLogo() {
        return com.example.wtcspaddock.api.Constants.fixImageUrl(logo);
    }

    public String getColor() {
        if (color != null && color.length() == 4) { // Fix #666
            char r = color.charAt(1); char g = color.charAt(2); char b = color.charAt(3);
            return "#" + r + r + g + g + b + b;
        }
        return color;
    }

    public String getLiveryImage() {
        return com.example.wtcspaddock.api.Constants.fixImageUrl(liveryImage);
    }

    public boolean isPrivateer() { return "privateer".equalsIgnoreCase(type); }

    // Subclases
    public static class TeamStats {
        @SerializedName("active_drivers") public int activeDrivers;
        public int wins;
        public int podiums;
        @SerializedName("total_points") public int totalPoints;
    }

    public static class TeamSpecs {
        public String chassis;
        public String engine;
        public String power;
        public String layout;
        public String gearbox;
    }

    public static class RosterMember {
        public int id;
        public String name;
        public String nationality;
        public String role; // "Primary", "Reserve"
        public String avatar;

        public String getAvatar() {
            return com.example.wtcspaddock.api.Constants.fixImageUrl(avatar);
        }
    }
}