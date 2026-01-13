package com.example.wtcspaddock.ui.news;

import android.os.Bundle;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import com.bumptech.glide.Glide;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.News;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class NewsDetailFragment extends Fragment {
    private static final String ARG_ID = "news_id";
    private int newsId;

    public static NewsDetailFragment newInstance(int id) {
        NewsDetailFragment f = new NewsDetailFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_ID, id);
        f.setArguments(args);
        return f;
    }

    @Nullable @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_news_detail, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        if (getArguments() != null) newsId = getArguments().getInt(ARG_ID);

        RetrofitClient.getApiService().getNewsDetail(newsId).enqueue(new Callback<News>() {
            @Override
            public void onResponse(Call<News> call, Response<News> response) {
                if (response.isSuccessful() && response.body() != null) {
                    updateUI(response.body());
                }
            }
            @Override public void onFailure(Call<News> call, Throwable t) {}
        });
    }

    private void updateUI(News news) {
        View v = getView();
        if (v == null) return;

        ((TextView)v.findViewById(R.id.tvDetailTitle)).setText(news.getTitle());
        ((TextView)v.findViewById(R.id.tvDetailDate)).setText("PUBLISHED ON " + news.getDate().toUpperCase());

        // Renderizar HTML básico (negritas, saltos de línea)
        TextView tvContent = v.findViewById(R.id.tvDetailContent);
        tvContent.setText(Html.fromHtml(news.getContent(), Html.FROM_HTML_MODE_COMPACT));

        ImageView img = v.findViewById(R.id.imgDetailCover);
        if (news.getImageUrl() != null) Glide.with(this).load(news.getImageUrl()).into(img);
    }
}