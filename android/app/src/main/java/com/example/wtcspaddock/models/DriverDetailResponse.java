package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;

import java.util.List;

public class DriverDetailResponse {

    @SerializedName("driver")
    private DriverInfo driver;

    @SerializedName("stats")
    private DriverStats stats;

    public DriverInfo getDriver() { return driver; }
    public DriverStats getStats() { return stats; }

    @SerializedName("history")
    private List<DriverHistory> history;

    public List<DriverHistory> getHistory() { return history; }


    // --- CLASES INTERNAS PARA ORGANIZAR ---

    public static class DriverInfo {
        private String name;
        private String nationality; // "ES"
        private String team;
        @SerializedName("team_color")
        private String teamColor;
        private String avatar;
        private String equipment; // "wheel" o "pad"
        private String bio;

        public String getName() { return name; }
        public String getNationality() { return nationality; }
        public String getTeam() { return team; }
        public String getBio() { return bio; }

        public String getTeamColor() {
            // Fix para colores cortos tipo #666 -> #666666
            if (teamColor != null && teamColor.length() == 4) {
                char r = teamColor.charAt(1);
                char g = teamColor.charAt(2);
                char b = teamColor.charAt(3);
                return "#" + r + r + g + g + b + b;
            }
            return teamColor;
        }

        public String getAvatar() {
            if (avatar != null && avatar.contains("127.0.0.1")) {
                return avatar.replace("127.0.0.1", "10.0.2.2");
            }
            return avatar;
        }

        public String getEquipment() { return equipment; }
    }

    public static class DriverStats {
        private int starts;
        private int wins;
        private int points;
        private int podiums;
        private int poles;

        public int getStarts() { return starts; }
        public int getWins() { return wins; }
        public int getPoints() { return points; }
        public int getPodiums() { return podiums; }
        public int getPoles() { return poles; }
    }
}