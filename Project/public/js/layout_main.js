console.log('Hello from layout_main.js!');

document.addEventListener('DOMContentLoaded', function() {
    console.log('The DOM is ready!');
    fetch('/api/groups')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            const groupsList = document.getElementById('groups-list');
            const searchInput = document.getElementById('search-groups');
            const searchGroupsForm = document.getElementById('search-groups-form');

            function filterGroups() {
                const filter = searchInput.value.toLowerCase();
                const groupItems = groupsList.getElementsByTagName('li');
                Array.from(groupItems).forEach(item => {
                    const groupName = item.querySelector('a span:nth-child(2)').textContent.toLowerCase();
                    if (groupName.includes(filter)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
            searchGroupsForm.addEventListener('submit', event => {
                event.preventDefault();
                filterGroups();
            });
            searchInput.addEventListener('input', filterGroups);

            data.forEach(group => {
                const groupItem = document.createElement('li');
                const groupLink = document.createElement('a');
                groupLink.href = '/group/' + group.id;
                const groupImg = document.createElement('span');
                groupImg.className = 'scrollable-list__img';
                groupImg.style.backgroundImage = `url('${group.profile_picture}')`;

                const groupName = document.createElement('span');
                groupName.textContent = group.name;
                const urlSegments = window.location.pathname.split('/');
                const groupId = urlSegments[urlSegments.length - 1];
                console.log(groupId, group.id);
                if (groupId && groupId == group.id) {
                    groupLink.classList.add('scrollable-list__selected');
                }
                groupLink.appendChild(groupImg);
                groupLink.appendChild(groupName);
                groupItem.appendChild(groupLink);
                groupsList.appendChild(groupItem);
            });
        });

});