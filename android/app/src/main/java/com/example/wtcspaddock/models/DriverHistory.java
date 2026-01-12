package com.example.wtcspaddock.models;
import com.google.gson.annotations.SerializedName;

public class DriverHistory {
    @SerializedName("round_number")
    private int roundNumber;

    @SerializedName("round_name")
    private String roundName;

    @SerializedName("race_pos")
    private int racePos;

    @SerializedName("qualy_pos")
    private int qualyPos;

    @SerializedName("qualy_time")
    private String qualyTime;

    @SerializedName("qualy_tyre")
    private String qualyTyre;

    @SerializedName("points_after_round")
    private int totalPoints;

    // Getters
    public int getRoundNumber() { return roundNumber; }
    public String getRoundName() { return roundName; }
    public int getRacePos() { return racePos; }
    public int getQualyPos() { return qualyPos; }
    public String getQualyTime() { return qualyTime; }
    public String getQualyTyre() { return qualyTyre; }
    public int getTotalPoints() { return totalPoints; }
}