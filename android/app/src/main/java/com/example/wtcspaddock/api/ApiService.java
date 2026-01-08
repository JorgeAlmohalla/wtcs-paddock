package com.example.wtcspaddock.api;

import com.example.wtcspaddock.models.CalendarResponse;
import com.example.wtcspaddock.models.DriverStanding;
import com.example.wtcspaddock.models.LoginRequest;
import com.example.wtcspaddock.models.LoginResponse;
import com.example.wtcspaddock.models.RoundDetailResponse;
import com.example.wtcspaddock.models.StandingsResponse;
import com.example.wtcspaddock.models.User;

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

    // 2. Obtener Calendario (Con respuesta envuelta en "data")
    @GET("calendar")
    Call<CalendarResponse> getCalendar();

    // 3. Obtener Clasificación (CORREGIDO: Usamos StandingsResponse)
    @GET("standings")
    Call<List<DriverStanding>> getStandings();

    // 4. Perfil de Usuario (Protegido)
    @GET("user")
    Call<User> getUserProfile(@Header("Authorization") String token);

    // Detalle de Ronda (Resultados)
    @GET("rounds/{id}")
    Call<RoundDetailResponse> getRoundDetails(@retrofit2.http.Path("id") int roundId);
}