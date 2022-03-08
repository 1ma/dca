namespace DCA.Model.Contracts;

public interface IBuyer<out T> where T : ICurrency
{
    public T Buy(Bitcoin amount);
}
