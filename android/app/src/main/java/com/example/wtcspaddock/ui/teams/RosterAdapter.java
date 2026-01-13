package com.example.wtcspaddock.ui.teams;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.example.wtcspaddock.MainActivity;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.TeamDetailResponse;

import java.util.List;

public class RosterAdapter extends RecyclerView.Adapter<RosterAdapter.ViewHolder> {

    private Context context;
    private List<TeamDetailResponse.RosterMember> roster;

    public RosterAdapter(Context context, List<TeamDetailResponse.RosterMember> roster) {
        this.context = context;
        this.roster = roster;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        // Reusamos la tarjeta de conductor que ya tiene el badgePrincipal oculto
        View view = LayoutInflater.from(context).inflate(R.layout.item_driver_card, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        TeamDetailResponse.RosterMember member = roster.get(position);

        holder.tvName.setText(member.name);

        // 1. Limpieza de vista (ocultamos lo que es de la lista general)
        holder.tvTeam.setVisibility(View.GONE);
        holder.tvFlag.setVisibility(View.GONE); // La bandera la pondremos junto al rol
        holder.tvRole.setVisibility(View.GONE); // El rol genérico fuera

        // 2. Determinar Rol (Primary vs Reserve)
        String roleString = member.role != null ? member.role.toLowerCase() : "";
        String displayRole = "PRIMARY"; // Por defecto
        int roleColor = Color.parseColor("#999999"); // Gris para texto

        if (roleString.contains("reserve")) {
            displayRole = "RESERVE";
            roleColor = Color.parseColor("#FF5555"); // Rojo suave para reserva si quieres
        }

        // 3. Mostrar Texto "Bandera + PRIMARY/RESERVE"
        holder.tvRosterRole.setVisibility(View.VISIBLE);
        String flag = getFlagEmoji(member.nationality);
        holder.tvRosterRole.setText(flag + "  " + displayRole);
        // holder.tvRosterRole.setTextColor(roleColor); // Descomenta si quieres color específico

        // 4. Lógica TEAM PRINCIPAL (Badge Dorado a la derecha)
        if (roleString.contains("principal")) {
            holder.badgePrincipal.setVisibility(View.VISIBLE);
            holder.badgePrincipal.setText("Team Principal");
            // Estilo Dorado Web
            holder.badgePrincipal.setTextColor(Color.parseColor("#FFD700"));
            // Borde dorado fino (opcional) o fondo transparente
            holder.badgePrincipal.setBackgroundColor(Color.parseColor("#26FFD700")); // Fondo muy suave
        } else {
            holder.badgePrincipal.setVisibility(View.GONE);
        }

        // 5. Avatar
        if (member.getAvatar() != null) {
            Glide.with(context).load(member.getAvatar()).apply(RequestOptions.circleCropTransform()).into(holder.imgAvatar);
            holder.tvInitials.setVisibility(View.GONE);
        } else {
            holder.imgAvatar.setImageDrawable(null);
            holder.tvInitials.setVisibility(View.VISIBLE);
            holder.tvInitials.setText((member.name != null && !member.name.isEmpty()) ? member.name.substring(0, 1) : "?");
        }

        // Click
        holder.itemView.setOnClickListener(v -> {
            if (context instanceof MainActivity) ((MainActivity)context).navigateToDriverDetail(member.id);
        });
    }

    // Helper de bandera (Cópialo del DriversAdapter si no lo tienes aquí, o hazlo util estático)
    private String getFlagEmoji(String countryCode) {
        if (countryCode == null) return "";
        try {
            int firstLetter = Character.codePointAt(countryCode, 0) - 0x41 + 0x1F1E6;
            int secondLetter = Character.codePointAt(countryCode, 1) - 0x41 + 0x1F1E6;
            return new String(Character.toChars(firstLetter)) + new String(Character.toChars(secondLetter));
        } catch (Exception e) { return ""; }
    }

    @Override
    public int getItemCount() { return roster.size(); }

    // --- VIEWHOLDER CORREGIDO ---
    static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvName, tvTeam, tvRole, tvInitials, badgePrincipal, tvFlag, tvRosterRole; // <--- Nuevo
        ImageView imgAvatar;

        public ViewHolder(View v) {
            super(v);
            tvName = v.findViewById(R.id.tvDriverName);
            tvTeam = v.findViewById(R.id.tvTeamName);
            tvRole = v.findViewById(R.id.tvRole);
            tvInitials = v.findViewById(R.id.tvInitials);
            imgAvatar = v.findViewById(R.id.imgAvatar);
            badgePrincipal = v.findViewById(R.id.badgePrincipal);
            tvFlag = v.findViewById(R.id.tvFlag);

            // NUEVO
            tvRosterRole = v.findViewById(R.id.tvRosterRole);
        }
    }
}