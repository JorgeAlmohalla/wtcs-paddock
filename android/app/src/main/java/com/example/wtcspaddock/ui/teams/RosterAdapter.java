package com.example.wtcspaddock.ui.teams;

import android.content.Context;
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
        // Reusamos item_driver_card porque es casi igual, o creas uno simple
        // Para simplificar, usaremos item_driver_card pero quitando el borde
        View view = LayoutInflater.from(context).inflate(R.layout.item_driver_card, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        TeamDetailResponse.RosterMember member = roster.get(position);

        holder.tvName.setText(member.name);
        holder.tvRole.setText(member.role); // "Primary" / "Reserve"
        holder.tvTeam.setVisibility(View.GONE); // No hace falta, ya estamos en el equipo

        // Avatar
        if (member.getAvatar() != null) {
            Glide.with(context).load(member.getAvatar()).apply(RequestOptions.circleCropTransform()).into(holder.imgAvatar);
            holder.tvInitials.setVisibility(View.GONE);
        } else {
            holder.imgAvatar.setImageDrawable(null);
            holder.tvInitials.setVisibility(View.VISIBLE);
            holder.tvInitials.setText(member.name.substring(0,1));
        }

        // Click al perfil del piloto
        holder.itemView.setOnClickListener(v -> {
            if (context instanceof MainActivity) ((MainActivity)context).navigateToDriverDetail(member.id);
        });
    }

    @Override
    public int getItemCount() { return roster.size(); }

    static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvName, tvTeam, tvRole, tvInitials;
        ImageView imgAvatar;
        public ViewHolder(View v) {
            super(v);
            tvName = v.findViewById(R.id.tvDriverName);
            tvTeam = v.findViewById(R.id.tvTeamName);
            tvRole = v.findViewById(R.id.tvRole); // Asegúrate de tener este ID en item_driver_card.xml
            tvInitials = v.findViewById(R.id.tvInitials);
            imgAvatar = v.findViewById(R.id.imgAvatar);
        }
    }
}