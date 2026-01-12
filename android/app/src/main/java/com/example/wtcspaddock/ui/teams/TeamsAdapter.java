package com.example.wtcspaddock.ui.teams;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.wtcspaddock.MainActivity;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.Team;
import com.google.android.material.card.MaterialCardView;

import java.util.ArrayList;
import java.util.List;

public class TeamsAdapter extends RecyclerView.Adapter<TeamsAdapter.ViewHolder> {

    private Context context;
    private List<Team> fullList;
    private List<Team> filteredList;

    public TeamsAdapter(Context context, List<Team> teams) {
        this.context = context;
        this.fullList = new ArrayList<>(teams);
        this.filteredList = teams;
    }

    public void filter(String text) {
        List<Team> temp = new ArrayList<>();
        if (text == null || text.isEmpty()) {
            temp.addAll(fullList);
        } else {
            for (Team t : fullList) {
                if (t.getName().toLowerCase().contains(text.toLowerCase())) {
                    temp.add(t);
                }
            }
        }
        filteredList = temp;
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_team_card, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Team team = filteredList.get(position);

        holder.tvName.setText(team.getName());
        holder.tvCar.setText(team.getCar());

        // --- GESTIÓN DEL COLOR ---
        try {
            int teamColor = Color.parseColor(team.getColor());

            // 1. Pintar la franja lateral
            holder.viewStrip.setBackgroundColor(teamColor);

            // 2. Pintar el borde de la tarjeta (AHORA SÍ FUNCIONARÁ)
            holder.card.setStrokeColor(teamColor);

        } catch (Exception e) {
            // Fallback si el color viene mal
            holder.viewStrip.setBackgroundColor(Color.GRAY);
            holder.card.setStrokeColor(Color.GRAY);
        }

        // --- BADGE WORKS/PRIVATEER ---
        if (team.isPrivateer()) {
            holder.tvType.setText("PRIVATEER");
            holder.tvType.setTextColor(Color.parseColor("#80D8FF"));
            holder.tvType.setBackgroundColor(Color.parseColor("#2080D8FF"));
        } else {
            holder.tvType.setText("WORKS");
            holder.tvType.setTextColor(Color.parseColor("#4CAF50"));
            holder.tvType.setBackgroundColor(Color.parseColor("#204CAF50"));
        }

        // Click -> Detalle
        holder.itemView.setOnClickListener(v -> {
            if (context instanceof MainActivity) {
                ((MainActivity) context).navigateToTeamDetail(team.getId());
            }
        });
    }

    @Override
    public int getItemCount() { return filteredList.size(); }

    // --- VIEWHOLDER CORREGIDO ---
    public static class ViewHolder extends RecyclerView.ViewHolder {
        MaterialCardView card; // <--- ESTA VARIABLE FALTABA
        TextView tvName, tvCar, tvType;
        View viewStrip;

        public ViewHolder(View v) {
            super(v);
            // Casteamos la vista raíz a MaterialCardView
            card = (MaterialCardView) v;

            tvName = v.findViewById(R.id.tvTeamName);
            tvCar = v.findViewById(R.id.tvCarModel);
            tvType = v.findViewById(R.id.tvTeamType);
            viewStrip = v.findViewById(R.id.viewTeamStrip);
        }
    }
}