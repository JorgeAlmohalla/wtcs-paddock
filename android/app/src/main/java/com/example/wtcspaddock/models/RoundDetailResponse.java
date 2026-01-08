package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class RoundDetailResponse {
    @SerializedName("round_number")
    private int roundNumber;

    // Qualy es una lista directa
    private List<ResultRow> qualifying;

    // Sprint y Feature son objetos
    @SerializedName("sprint_race")
    private RaceSession sprintRace;

    @SerializedName("feature_race")
    private RaceSession featureRace;

    // Getters
    public List<ResultRow> getQualifying() { return qualifying; }
    public List<ResultRow> getSprintResults() {
        return sprintRace != null ? sprintRace.getResults() : null;
    }
    public List<ResultRow> getFeatureResults() {
        return featureRace != null ? featureRace.getResults() : null;
    }
}