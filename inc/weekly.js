const monthColor = [
  "LightBlue",   // January – cold/winter sky
  "White",       // February – snow / purity
  "Aquamarine",  // March – spring hint
  "LightGreen",  // April – fresh growth
  "LimeGreen",   // May – full spring
  "Gold",        // June – summer sun
  "Orange",      // July – warmth / midsummer
  "Tomato",      // August – late summer heat
  "Olive",       // September – autumn tones
  "OrangeRed",   // October – fall leaves
  "Brown",       // November – bare earth
  "LightSteelBlue" // December – frost / cold winter
];


function fadeBackground() {
  const today = new Date();
  const y  = today.getFullYear();
  const m  = today.getMonth();                                // 0..11
  const d  = today.getDate();                                  // 1..
  const dim = new Date(y, m+1, 0).getDate();

  let prevColor = monthColor[(m+11) % 12];
  let thisColor = monthColor[m];
  let nextColor = monthColor[(m+1) % 12];

  // Last 7 days: ~14.2857% step per day
  const startLastWeek = dim - 6;                               // e.g. 24
  let nextPct = 0;
  if (d >= startLastWeek) {
    const dayIndex = d - startLastWeek - 1;                        // 0..6
    nextPct = Math.min(100, Math.round(((dayIndex + 1) / 7) * 100));
//    alert (nextPct);
  }
  else if (d <= 15 ) {
    nextPct = 90;
    nextColor = thisColor;
    thisColor = prevColor;
  }
  else {
    nextPct = 10;
  }
  const thisPct = 100 - nextPct;

  // Soft edge between colors
  const FEATHER = 10;                                           // % width of blend
  const edgeStart = Math.max(0, Math.min(100, thisPct - FEATHER/2));
  const edgeEnd   = Math.max(0, Math.min(100, thisPct + FEATHER/2));

  // Base body color = current month (nice initial state)
  document.body.style.background = thisColor;

  // Overlay gradient (fades in via opacity)
  const gradient = `linear-gradient(to right,
    ${thisColor} 0%,
    ${thisColor} ${edgeStart}%,
    ${nextColor} ${edgeEnd}%,
    ${nextColor} 100%)`;

  const bg = document.getElementById('bg');
  bg.style.background = gradient;

  // Let layout settle, then fade overlay to 1 (visible)
  requestAnimationFrame(() => { bg.style.opacity = 1; });

  setTimeout(() => {
    document.getElementById('ops').style.visibility = 'hidden';
  }, 10000);

}

function fullWeek() {
  // alert("test");
  const days = ["mo","tu","we","th","fr","sa","su"];

  days.forEach(dayId => {
    const el = document.getElementById(dayId);
    if (el) {
      // Do your checks here
      if (el.checked == true) el.checked = false; // example
      else if (el.checked == false) el.checked = true;
    }
  }); 
}
/*
const monthColor2 = [
  "#9A2A2A", // January – Garnet (deep red)
  "#9966CC", // February – Amethyst (purple violet)
  "#7FFFD4", // March – Aquamarine (light blue-green)
  "#E6E8FA", // April – Diamond (near white / icy sparkle)
  "#50C878", // May – Emerald (vivid green)
  "#E0B0FF", // June – Alexandrite / Pearl (soft lilac)
  "#E0115F", // July – Ruby (vivid pink-red)
  "#B4EEB4", // August – Peridot (light green)
  "#0F52BA", // September – Sapphire (royal blue)
  "#FF69B4", // October – Tourmaline / Opal (pink tones)
  "#FFC87C", // November – Topaz / Citrine (golden yellow)
  "#40E0D0"  // December – Turquoise / Blue Zircon (blue-green)
];
*/

