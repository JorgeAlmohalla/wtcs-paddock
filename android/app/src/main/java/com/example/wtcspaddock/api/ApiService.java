package com.example.wtcspaddock.api;

import com.example.wtcspaddock.models.LoginResponse;
import com.example.wtcspaddock.models.Race;
import com.example.wtcspaddock.models.User;
import com.example.wtcspaddock.models.LoginRequest;

import java.util.List;
import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.POST;

public interface ApiService {

    // 1. Login
    @POST("login")
    Call<LoginResponse> login(@Body LoginRequest request);

    // 2. Obtener Calendario
    @GET("calendar")
    Call<List<Race>> getCalendar();

    // 3. Obtener Clasificación (Standings)
    @GET("standings")
    Call<List<User>> getStandings();

    // 4. Perfil de Usuario (Protegido)
    @GET("user")
    Call<User> getUserProfile(@Header("Authorization") String token);
}