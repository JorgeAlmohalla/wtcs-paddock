package com.example.wtcspaddock.ui.standings;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.viewpager2.adapter.FragmentStateAdapter;
import androidx.viewpager2.widget.ViewPager2;

import com.example.wtcspaddock.R;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;

public class StandingsFragment extends Fragment {

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_standings, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        TabLayout tabLayout = view.findViewById(R.id.tabLayoutStandings);
        ViewPager2 viewPager = view.findViewById(R.id.viewPagerStandings);

        // Configuramos el adaptador de pestañas
        StandingsPagerAdapter adapter = new StandingsPagerAdapter(this);
        viewPager.setAdapter(adapter);

        // Vinculamos nombres de pestañas
        new TabLayoutMediator(tabLayout, viewPager, (tab, position) -> {
            switch (position) {
                case 0: tab.setText("DRIVERS"); break;
                case 1: tab.setText("CONSTRUCTORS"); break;
                case 2: tab.setText("MANUFACTURERS"); break;
            }
        }).attach();
    }

    // --- ADAPTADOR INTERNO ---
    class StandingsPagerAdapter extends FragmentStateAdapter {
        public StandingsPagerAdapter(@NonNull Fragment fragment) {
            super(fragment);
        }

        @NonNull
        @Override
        public Fragment createFragment(int position) {
            // Aquí crearemos un fragmento genérico que pida distintos datos
            String type = "drivers";
            if (position == 1) type = "constructors";
            if (position == 2) type = "manufacturers";

            return StandingsListFragment.newInstance(type);
        }

        @Override
        public int getItemCount() { return 3; }
    }
}