public function {{name}}()
{
    return $this->belongsTo({{class}}, '{{foreign_key}}', '{{primary_key}}');
}