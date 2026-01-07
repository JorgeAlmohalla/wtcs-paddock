package com.example.wtcspaddock.models;
import com.google.gson.annotations.SerializedName;

public class LoginResponse {
    // Ajusta "token" si tu Laravel devuelve "access_token"
    @SerializedName("token")
    private String token;

    private User user;

    public String getToken() { return token; }
    public User getUser() { return user; }
}