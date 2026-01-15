package com.example.wtcspaddock.api;

import com.example.wtcspaddock.models.CalendarResponse;
import com.example.wtcspaddock.models.Driver;
import com.example.wtcspaddock.models.DriverDetailResponse;
import com.example.wtcspaddock.models.DriverStanding;
import com.example.wtcspaddock.models.LoginRequest;
import com.example.wtcspaddock.models.LoginResponse;
import com.example.wtcspaddock.models.ManufacturerStanding;
import com.example.wtcspaddock.models.News;
import com.example.wtcspaddock.models.RoundDetailResponse;
import com.example.wtcspaddock.models.StandingsResponse;
import com.example.wtcspaddock.models.Team;
import com.example.wtcspaddock.models.TeamDetailResponse;
import com.example.wtcspaddock.models.TeamStanding;
import com.example.wtcspaddock.models.User;
import com.example.wtcspaddock.models.Report;
import com.example.wtcspaddock.models.FormData;

import java.util.List;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.Field;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.Multipart;
import retrofit2.http.POST;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.http.Part;

public interface ApiService {

    // Login
    @POST("login")
    Call<LoginResponse> login(@Body LoginRequest request);

    // Obtener Calendario (Con respuesta envuelta en "data")
    @GET("calendar")
    Call<CalendarResponse> getCalendar();

    // Obtener Clasificación (CORREGIDO: Usamos StandingsResponse)
    @GET("standings")
    Call<List<DriverStanding>> getStandings();

    // Perfil de Usuario (Protegido)
    @GET("user")
    Call<User> getUserProfile(@Header("Authorization") String token);

    // Detalle de Ronda (Resultados)
    @GET("rounds/{id}")
    Call<RoundDetailResponse> getRoundDetails(@retrofit2.http.Path("id") int roundId);

    // Drivers
    @GET("standings")
    Call<List<DriverStanding>> getDriverStandings();

    // Team
    @GET("standings/teams")
    Call<List<TeamStanding>> getTeamStandings();

    // Manufacturers
    @GET("standings/manufacturers")
    Call<List<ManufacturerStanding>> getManufacturerStandings();

    @GET("drivers") // Asegúrate de que esta sea la ruta correcta en tu Laravel
    Call<List<Driver>> getDrivers();

    // Detalle de Piloto
    @GET("drivers/{id}")
    Call<DriverDetailResponse> getDriverDetails(@retrofit2.http.Path("id") int driverId);

    // 5. LISTA DE EQUIPOS
    @GET("teams")
    Call<List<Team>> getTeams();

    @GET("teams/{id}")
    Call<TeamDetailResponse> getTeamDetails(@retrofit2.http.Path("id") int teamId);

    // LISTA DE NOTICIAS
    @GET("news")
    Call<List<News>> getNewsList();

    // DETALLE DE NOTICIA
    @GET("news/{id}")
    Call<News> getNewsDetail(@retrofit2.http.Path("id") int newsId);

    // ACTUALIZAR PERFIL (Multipart para la foto)
    @Multipart
    @POST("user/update-profile")
    Call<Void> updateProfile(
            @Header("Authorization") String token,
            @Part("name") RequestBody name,
            @Part("email") RequestBody email,
            @Part("nationality") RequestBody nationality,
            @Part("steam_id") RequestBody steamId,
            @Part("equipment") RequestBody equipment,
            @Part("driver_number") RequestBody number,
            @Part("bio") RequestBody bio,
            @Part MultipartBody.Part avatar // La imagen (puede ser null)
    );

    // CAMBIAR PASSWORD
    @FormUrlEncoded
    @POST("user/change-password")
    Call<Void> changePassword(
            @Header("Authorization") String token,
            @Field("current_password") String current,
            @Field("password") String newPass,
            @Field("password_confirmation") String confirmPass
    );

    // 1. Obtener el historial de reportes del usuario
    @GET("user/reports")
    Call<List<Report>> getUserReports(@Header("Authorization") String token);

    // 2. Obtener listas para el formulario (Carreras y Pilotos)
    @GET("form-data")
    Call<FormData> getFormData(@Header("Authorization") String token);

    // 3. ENVIAR EL REPORTE
    @FormUrlEncoded // <--- ¡ESTO ES LO QUE FALTABA Y PROVOCABA EL CRASH!
    @POST("incidents")
    Call<Void> submitReport(
            @Header("Authorization") String token,
            @Field("race_id") int raceId,
            @Field("accused_driver_id") int driverId,
            @Field("lap_corner") String lapCorner,
            @Field("description") String description,
            @Field("video_url") String videoUrl
    );
}