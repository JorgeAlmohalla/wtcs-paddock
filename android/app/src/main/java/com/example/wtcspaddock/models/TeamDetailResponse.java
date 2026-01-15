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
        if (logo == null) return null;

        // 1. Si viene con localhost o 127.0.0.1, lo cambiamos a la IP del emulador
        if (logo.contains("127.0.0.1")) {
            return logo.replace("127.0.0.1", "10.0.2.2");
        } else if (logo.contains("localhost")) {
            return logo.replace("localhost", "10.0.2.2");
        }

        // Si estás usando móvil físico con IP local (ej: 192.168.1.35),
        // asegúrate de que Laravel esté enviando esa IP o haz el replace aquí también:
        // if (logo.contains("127.0.0.1")) return logo.replace("127.0.0.1", "192.168.1.35");

        return logo;
    }

    public String getColor() {
        if (color != null && color.length() == 4) { // Fix #666
            char r = color.charAt(1); char g = color.charAt(2); char b = color.charAt(3);
            return "#" + r + r + g + g + b + b;
        }
        return color;
    }

    public String getLiveryImage() {
        if (liveryImage == null) return null;
        // Misma lógica
        if (liveryImage.contains("127.0.0.1")) return liveryImage.replace("127.0.0.1", "10.0.2.2");
        if (liveryImage.contains("localhost")) return liveryImage.replace("localhost", "10.0.2.2");

        return liveryImage;
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
            if (avatar != null && avatar.contains("127.0.0.1")) return avatar.replace("127.0.0.1", "10.0.2.2");
            return avatar;
        }
    }
}