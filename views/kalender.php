      <div class="container-fluid">
          <div class="row">
              <div class="col-md-3">
                  <div class="sticky-top mb-3">
                      <div class="card">
                          <div class="card-header">
                              <h4 class="card-title">Draggable Events</h4>
                          </div>
                          <div class="card-body">
                              <!-- the events -->
                              <div id="external-events">
                                  <div class="external-event bg-success">Lunch</div>
                                  <div class="external-event bg-warning">Go home</div>
                                  <div class="external-event bg-info">Do homework</div>
                                  <div class="external-event bg-primary">Work on UI design</div>
                                  <div class="external-event bg-danger">Sleep tight</div>
                                  <div class="checkbox">
                                      <label for="drop-remove">
                                          <input type="checkbox" id="drop-remove">
                                          remove after drop
                                      </label>
                                  </div>
                              </div>
                          </div>
                          <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                      <div class="card">
                          <div class="card-header">
                              <h3 class="card-title">Create Event</h3>
                          </div>
                          <div class="card-body">
                              <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                                  <ul class="fc-color-picker" id="color-chooser">
                                      <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                                      <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                                      <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                                      <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                                      <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                                  </ul>
                              </div>
                              <!-- /btn-group -->
                              <div class="input-group">
                                  <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                                  <div class="input-group-append">
                                      <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                                  </div>
                                  <!-- /btn-group -->
                              </div>
                              <!-- /input-group -->
                          </div>
                      </div>
                  </div>
              </div>
              <!-- /.col -->
              <div class="col-md-9">
                  <div class="card card-primary">
                      <div class="card-body p-0">
                          <!-- THE CALENDAR -->
                          <div id="calendar"></div>
                      </div>
                      <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
              </div>
              <!-- /.col -->
          </div>
          <!-- /.row -->
      </div><!-- /.container-fluid -->

      <script>
          // Buat file terpisah misalnya calendar.js
          document.addEventListener('DOMContentLoaded', function() {
              // Pastikan elemen ada sebelum inisialisasi
              const calendarEl = document.getElementById('calendar');
              if (!calendarEl) return;

              const date = new Date();
              const y = date.getFullYear();
              const m = date.getMonth();
              const d = date.getDate();
              const containerEl = document.getElementById('external-events');
              const checkbox = document.getElementById('drop-remove');

              // Inisialisasi draggable events
              if (containerEl) {
                  new FullCalendar.Draggable(containerEl, {
                      itemSelector: '.external-event',
                      eventData: function(eventEl) {
                          return {
                              title: eventEl.innerText,
                              backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                              borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                              textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                          };
                      }
                  });
              }

              // Inisialisasi calendar
              const calendar = new FullCalendar.Calendar(calendarEl, {
                  headerToolbar: {
                      left: 'prev,next today',
                      center: 'title',
                      right: 'dayGridMonth,timeGridWeek,timeGridDay'
                  },
                  themeSystem: 'bootstrap',
                  editable: true,
                  droppable: true,
                  events: [{
                          title: 'All Day Event',
                          start: new Date(y, m, 1),
                          backgroundColor: '#f56954', //red
                          borderColor: '#f56954', //red
                          allDay: true
                      },
                      {
                          title: 'Long Event',
                          start: new Date(y, m, d - 5),
                          end: new Date(y, m, d - 2),
                          backgroundColor: '#f39c12', //yellow
                          borderColor: '#f39c12' //yellow
                      },
                      {
                          title: 'Meeting',
                          start: new Date(y, m, d, 10, 30),
                          allDay: false,
                          backgroundColor: '#0073b7', //Blue
                          borderColor: '#0073b7' //Blue
                      },
                      {
                          title: 'Lunch',
                          start: new Date(y, m, d, 12, 0),
                          end: new Date(y, m, d, 14, 0),
                          allDay: false,
                          backgroundColor: '#00c0ef', //Info (aqua)
                          borderColor: '#00c0ef' //Info (aqua)
                      },
                      {
                          title: 'Birthday Party',
                          start: new Date(y, m, d + 1, 19, 0),
                          end: new Date(y, m, d + 1, 22, 30),
                          allDay: false,
                          backgroundColor: '#00a65a', //Success (green)
                          borderColor: '#00a65a' //Success (green)
                      },
                      {
                          title: 'Click for Google',
                          start: new Date(y, m, 28),
                          end: new Date(y, m, 29),
                          url: 'https://www.google.com/',
                          backgroundColor: '#3c8dbc', //Primary (light-blue)
                          borderColor: '#3c8dbc' //Primary (light-blue)
                      }
                  ],
                  drop: function(info) {
                      if (checkbox && checkbox.checked) {
                          info.draggedEl.parentNode.removeChild(info.draggedEl);
                      }
                  }
              });

              calendar.render();

              // Handle color picker
              const colorChooser = document.querySelectorAll('#color-chooser > li > a');
              let currColor = '#3c8dbc';

              colorChooser.forEach(color => {
                  color.addEventListener('click', function(e) {
                      e.preventDefault();
                      currColor = window.getComputedStyle(this).getPropertyValue('color');
                      const addEventBtn = document.getElementById('add-new-event');
                      if (addEventBtn) {
                          addEventBtn.style.backgroundColor = currColor;
                          addEventBtn.style.borderColor = currColor;
                      }
                  });
              });

              // Handle add new event
              const addEventBtn = document.getElementById('add-new-event');
              const newEventInput = document.getElementById('new-event');

              if (addEventBtn && newEventInput) {
                  addEventBtn.addEventListener('click', function(e) {
                      e.preventDefault();
                      const val = newEventInput.value.trim();
                      if (val.length === 0) return;

                      const event = document.createElement('div');
                      event.className = 'external-event';
                      event.style.backgroundColor = currColor;
                      event.style.borderColor = currColor;
                      event.style.color = '#fff';
                      event.innerText = val;

                      const externalEvents = document.getElementById('external-events');
                      if (externalEvents) {
                          externalEvents.prepend(event);
                          newEventInput.value = '';

                          // Reinitialize draggable
                          new FullCalendar.Draggable(event, {
                              eventData: function() {
                                  return {
                                      title: event.innerText,
                                      backgroundColor: currColor,
                                      borderColor: currColor
                                  };
                              }
                          });
                      }
                  });
              }
          });
      </script>