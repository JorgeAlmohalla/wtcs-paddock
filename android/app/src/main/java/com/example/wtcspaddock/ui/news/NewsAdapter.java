package com.example.wtcspaddock.ui.news;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.bumptech.glide.Glide;
import com.example.wtcspaddock.MainActivity;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.News;
import java.util.List;

public class NewsAdapter extends RecyclerView.Adapter<NewsAdapter.ViewHolder> {

    private Context context;
    private List<News> newsList;

    public NewsAdapter(Context context, List<News> newsList) {
        this.context = context;
        this.newsList = newsList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_news_card, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        News news = newsList.get(position);

        holder.tvTitle.setText(news.getTitle());
        holder.tvDate.setText(news.getDate());

        if (news.getImageUrl() != null) {
            Glide.with(context).load(news.getImageUrl()).centerCrop().into(holder.imgCover);
        }

        // Click -> Detalle
        holder.itemView.setOnClickListener(v -> {
            if (context instanceof MainActivity) {
                ((MainActivity) context).navigateToNewsDetail(news.getId());
            }
        });
    }

    @Override
    public int getItemCount() { return newsList.size(); }

    static class ViewHolder extends RecyclerView.ViewHolder {
        ImageView imgCover;
        TextView tvDate, tvTitle;

        public ViewHolder(View v) {
            super(v);
            imgCover = v.findViewById(R.id.imgNewsCover);
            tvDate = v.findViewById(R.id.tvNewsDate);
            tvTitle = v.findViewById(R.id.tvNewsTitle);
        }
    }
}