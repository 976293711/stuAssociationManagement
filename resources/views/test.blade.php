<html>
<body>
<h1>Hello, {{ $name }}</h1>
</body>


@ifHasRole('roleSlug1|roleSlug2')
<p>You can see this.</p>
@endIfHasRole

@ifHasPermission('permission1|permission2')
<p>You can see this too.</p>
@endIfHasPermission

</html>