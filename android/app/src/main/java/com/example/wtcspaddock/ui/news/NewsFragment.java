package com.example.wtcspaddock.ui.news;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.News;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class NewsFragment extends Fragment {

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Reusamos el layout de lista genérico (solo tiene un RecyclerView)
        return inflater.inflate(R.layout.fragment_calendar_list, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        RecyclerView rv = view.findViewById(R.id.recyclerCalendar); // ID reusado
        rv.setLayoutManager(new LinearLayoutManager(getContext()));

        // Cargar Noticias
        RetrofitClient.getApiService().getNewsList().enqueue(new Callback<List<News>>() {
            @Override
            public void onResponse(Call<List<News>> call, Response<List<News>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    rv.setAdapter(new NewsAdapter(getContext(), response.body()));
                }
            }
            @Override
            public void onFailure(Call<List<News>> call, Throwable t) {}
        });
    }
}