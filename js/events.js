const eventsData = {
  upcoming: [
    {
      id: 1,
      title: "Campus Revival Week",
      date: "November 15-22, 2025",
      location: "MMUST Main Campus",
      description: "A week-long revival event featuring powerful sermons, worship sessions, and altar calls. Join us as we seek God's presence on campus.",
      image: "https://images.pexels.com/photos/8468082/pexels-photo-8468082.jpeg?auto=compress&cs=tinysrgb&w=600"
    },
    {
      id: 2,
      title: "Kakamega Market Outreach",
      date: "November 30, 2025",
      location: "Kakamega Town Market",
      description: "Join us as we take the gospel to the marketplace. We'll share testimonies, distribute tracts, and minister to traders and shoppers.",
      image: "https://images.pexels.com/photos/3270224/pexels-photo-3270224.jpeg?auto=compress&cs=tinysrgb&w=600"
    },
    {
      id: 3,
      title: "Hospital Ministry Day",
      date: "December 7, 2025",
      location: "Kakamega County General Hospital",
      description: "A day of ministering hope and comfort to patients and their families. We'll pray for the sick and share God's love.",
      image: "https://images.pexels.com/photos/8460207/pexels-photo-8460207.jpeg?auto=compress&cs=tinysrgb&w=600"
    }
  ],
  past: [
    {
      id: 4,
      title: "Street Evangelism - Lurambi",
      date: "October 12, 2025",
      location: "Lurambi Market",
      description: "Over 200 souls received salvation as we ministered in Lurambi. The Holy Spirit moved powerfully through testimonies and worship.",
      image: "https://images.pexels.com/photos/5206963/pexels-photo-5206963.jpeg?auto=compress&cs=tinysrgb&w=600"
    },
    {
      id: 5,
      title: "Prison Ministry - Kakamega Prison",
      date: "September 28, 2025",
      location: "Kakamega Maximum Security Prison",
      description: "A transformative day ministering to inmates. Many gave their lives to Christ and we left them with Bibles and encouragement.",
      image: "https://images.pexels.com/photos/9347693/pexels-photo-9347693.jpeg?auto=compress&cs=tinysrgb&w=600"
    },
    {
      id: 6,
      title: "Freshers' Week Evangelism",
      date: "September 1-5, 2025",
      location: "MMUST Campus",
      description: "During freshers' week, over 150 new students committed their lives to Christ. Follow-up sessions are ongoing.",
      image: "https://images.pexels.com/photos/1708936/pexels-photo-1708936.jpeg?auto=compress&cs=tinysrgb&w=600"
    }
  ]
};

function renderEvents() {
  const upcomingContainer = document.getElementById('upcoming-events');
  const pastContainer = document.getElementById('past-events');

  if (upcomingContainer) {
    upcomingContainer.innerHTML = eventsData.upcoming.map(event => `
      <div class="event-card fade-in">
        <img src="${event.image}" alt="${event.title}">
        <div class="event-info">
          <h3>${event.title}</h3>
          <div class="event-meta">
            <span>📅 ${event.date}</span>
            <span>📍 ${event.location}</span>
          </div>
          <p>${event.description}</p>
          <a href="register.html" class="event-button">Join Event</a>
        </div>
      </div>
    `).join('');
  }

  if (pastContainer) {
    pastContainer.innerHTML = eventsData.past.map(event => `
      <div class="event-card fade-in">
        <img src="${event.image}" alt="${event.title}">
        <div class="event-info">
          <h3>${event.title}</h3>
          <div class="event-meta">
            <span>📅 ${event.date}</span>
            <span>📍 ${event.location}</span>
          </div>
          <p>${event.description}</p>
        </div>
      </div>
    `).join('');
  }
}

document.addEventListener('DOMContentLoaded', renderEvents);
